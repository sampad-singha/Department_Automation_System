<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseResourceController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'course_session_id' => 'required|exists:course_sessions,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:102400', // Max 100MB
        ]);

        $user = Auth::user();
        if ($user->courseSessions()->where('id', $request->course_session_id)->doesntExist()) {
            return response()->json(['message' => 'You are not authorized to upload resources for this course'], 403);
        }

        $file = $request->file('file');
        $filePath = $file->store('course_materials', 'local'); // Saved in `storage/app/private/course_materials`

        $resource = CourseResource::create([
            'course_session_id' => $request->course_session_id,
            'uploaded_by' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'resource' => $resource
        ], 201);
    }

    /**
     * List all course resources for a given course session.
     */
    public function index($course_session_id)
    {
        $user = Auth::user();

        if ($user->hasRole('teacher') && $user->courseSessions()->where('id', $course_session_id)->doesntExist()) {
            return response()->json(['message' => 'You are not authorized to view these resources'], 403);
        }
        if ($user->hasRole('student') && $user->enrollments()->where('courseSession_id', $course_session_id)->doesntExist()) {
            return response()->json(['message' => 'You are not enrolled in this course'], 403);
        }
        $resources = CourseResource::where('course_session_id', $course_session_id)->get()->makeHidden('file_path');

        return response()->json([
            'resources' => $resources
        ]);
    }

    /**
     * Download a private course resource.
     */
    public function download($id)
    {
        $resource = CourseResource::findOrFail($id);
        $user = Auth::user();
        if ($user->hasRole('teacher') && ($resource->uploaded_by !== $user->id)) {
            return response()->json(['message' => 'You are not authorized to download this file'], 403);
        }

        if ($user->hasRole('student') && $resource->courseSession->enrollments()->where('student_id', $user->id)->doesntExist()) {
            return response()->json(['message' => 'You are not enrolled in this course'], 403);
        }
        return Storage::download($resource->file_path, $resource->file_name);
    }
}
