<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EnrollmentController extends Controller
{
    public function store($courseSession): void
    {
        $course = $courseSession->course;

        // Fetch students matching the course's year and semester
        $students = User::role('teacher')
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

    public function show(Enrollment $enrollment)
    {
        return response()->json([
            'status' => 'success',
            'data' => $enrollment,
        ], Response::HTTP_OK);
    }
    public function showAll(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'year' => 'required|integer',
            'semester' => 'required|integer',
            'session' => 'required|string',
        ]);

        // Retrieve the filtered enrollments
        $enrollments = Enrollment::whereHas('courseSession', function ($query) use ($validatedData) {
            $query->where('session', $validatedData['session'])
                ->whereHas('course', function ($query) use ($validatedData) {
                    $query->where('year', $validatedData['year'])
                        ->where('semester', $validatedData['semester']);
                });
        })->get();

        return response()->json([
            'status' => 'success',
            'data' => $enrollments,
        ], Response::HTTP_OK);
    }

}
