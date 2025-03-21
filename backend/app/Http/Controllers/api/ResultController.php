<?php

namespace App\Http\Controllers\Api;

use App\Models\Enrollment;
use App\Helpers\GradeHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResultController extends Controller
{
    public function showResult($courseId)
    {

        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $enrollment = Enrollment::where('student_id', $user->id)
            ->whereHas('courseSession', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'User is not enrolled in this course'], 404);
        }

        $maxMarks = Enrollment::whereHas('courseSession', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
            ->max('final_term_marks');

        $gradeDetails = GradeHelper::getGrade($maxMarks);

        return response()->json([
            'course_id' => $courseId,
            'max_final_term_marks' => $maxMarks,
            'grade' => $gradeDetails['grade'],
            'gpa' => $gradeDetails['gpa'],
            'remark' => $gradeDetails['remark'],
            'user_id' => $user->id,
            'user_name' => $user->name,
        ], 200);
    }

   
    public function showFullResult($year, $semester)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $maxResults = Enrollment::where('student_id',  $user->id)
            ->whereHas('courseSession.course', function ($query) use ($year, $semester) {
                $query->where('year', $year);

                if (is_array($semester)) {
                    $query->whereIn('semester', $semester);
                } else {
                    $query->where('semester', $semester);
                }
            })
            ->with(['courseSession.course'])
            ->selectRaw('courseSession_id, MAX(final_term_marks) as max_final_term_marks')
            ->groupBy('courseSession_id')
            ->get();

        if ($maxResults->isEmpty()) {
            return response()->json(['message' => 'No results found for this student in this semester and year'], 404);
        }

        $response = $maxResults->map(function ($enrollment) {
            $gradeDetails = GradeHelper::getGrade($enrollment->max_final_term_marks);

            return [
                'course_id' => $enrollment->courseSession->course->id,
                'course_name' => $enrollment->courseSession->course->name,
                'year' => $enrollment->courseSession->course->year,
                'semester' => $enrollment->courseSession->course->semester,
                'max_final_term_marks' => $enrollment->max_final_term_marks,
                'grade' => $gradeDetails['grade'],
                'gpa' => $gradeDetails['gpa'],
                'remark' => $gradeDetails['remark'],
            ];
        });

        return response()->json([
            'courses' => $response
        ], 200);
    }
}
