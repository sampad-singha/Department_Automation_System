<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CourseSession;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CourseSessionController extends Controller
{
    public function show()
    {
        try {
            $teacher_id = Auth::id();
            $courseSessions = CourseSession::where('teacher_id', $teacher_id)->get();

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
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch course sessions.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
