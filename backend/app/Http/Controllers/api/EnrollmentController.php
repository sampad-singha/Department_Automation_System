<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EnrollmentController extends Controller
{
    public function store($courseSession): void
    {
        $this->authorize('create', Enrollment::class);
        $course = $courseSession->course;

        // Fetch students matching the course's year and semester
        $students = User::role('student')
            ->where('year', $course->year)
            ->where('semester', $course->semester)
            ->get();

        // Create enrollments for each matching student
        foreach ($students as $student) {
            Enrollment::create([
                'courseSession_id' => $courseSession->id,
                'student_id' => $student->id,
                'is_enrolled' => true, // Assuming enrollment is active upon creation
            ]);
        }
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'class_assessment_marks' => 'required|integer|min:0|max:30',
                'final_term_marks' => 'required|integer|min:0|max:70',
            ]);

            // Update only the specified fields
            $enrollment->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Enrollment updated successfully.',
                'data' => $enrollment,
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Enrollment update failed: '.$e->getMessage());

            // Handle other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showForTeacher($courseSessionId)
    {
        // Retrieve the authenticated teacher's ID
        $teacherId = Auth::id();

        // Fetch enrollments associated with the specified course_session_id
        // and ensure the course session belongs to the authenticated teacher
        $enrollments = Enrollment::whereHas('courseSession', function ($query) use ($courseSessionId, $teacherId) {
            $query->where('id', $courseSessionId)
                ->where('teacher_id', $teacherId);
        })->get();

        if ($enrollments->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'There is no enrollment data or you are not authorized to view it.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $enrollments,
        ]);
    }

    public function showForStudent(Request $request)
    {
        $studentId = Auth::id();

        // Start building the query
        $query = Enrollment::where('student_id', $studentId);

        // Apply optional filters if they are present in the request
        if ($request->filled('year')) {
            $query->where('year', $request->input('year'));
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }

        if ($request->filled('session')) {
            $query->where('session', $request->input('session'));
        }

        // Execute the query to get the filtered enrollments
        $enrollments = $query->get()->makeHidden('final_term_marks');

        if ($enrollments->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'There is no enrollment data or you are not authorized to view it.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $enrollments,
        ]);
    }

}
