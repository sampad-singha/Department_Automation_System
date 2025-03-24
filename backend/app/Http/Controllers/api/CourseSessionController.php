<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CourseSession;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CourseSessionController extends Controller
{
    public function show()
    {
        try {
            $teacher_id = Auth::id();
            // Fetch the latest session for each course the teacher teaches
            $courseSessions = CourseSession::with('course')
                ->where('teacher_id', $teacher_id)
                ->orderBy('session', 'desc') // Sort by latest session
                ->get()
                ->unique('course_id') // Keep only one session per course
                ->values(); // Re-index the collection

            if ($courseSessions->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No course sessions found.',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Course sessions fetched successfully.',
                'data' => $courseSessions,
            ], Response::HTTP_OK);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch course sessions.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
