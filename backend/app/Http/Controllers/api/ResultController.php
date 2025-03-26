<?php

namespace App\Http\Controllers\Api;

use App\Models\Enrollment;
use App\Helpers\GradeHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function showResult($courseId)
    {

        $user = Auth::user();

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


    // public function showFullResult($year, $semester)
    // {
    //     $user = Auth::id();
    //     if (!$user) {
    //         return response()->json(['message' => 'User not authenticated'], 401);
    //     }

    //     $maxResults = Enrollment::where('student_id',  $user)
    //         ->whereHas('courseSession.course', function ($query) use ($year, $semester) {
    //             $query->where('year', $year);

    //             if (is_array($semester)) {
    //                 $query->whereIn('semester', $semester);
    //             } else {
    //                 $query->where('semester', $semester);
    //             }
    //         })
    //         ->with(['courseSession.course'])
    //         ->selectRaw('courseSession_id, MAX(final_term_marks) as max_final_term_marks')
    //         ->groupBy('courseSession_id')
    //         ->get();

    //     if ($maxResults->isEmpty()) {
    //         return response()->json(['message' => 'No results found for this student in this semester and year'], 404);
    //     }
    //     $courseCredit=Course::select('credit')->get();
    //     //dd($courseCredit->toArray());

    //     $response = $maxResults->map(function ($enrollment) {
    //         $gradeDetails = GradeHelper::getGrade($enrollment->max_final_term_marks);

    //         return [
    //             'course_id' => $enrollment->courseSession->course->id,
    //             'course_name' => $enrollment->courseSession->course->name,
    //             'year' => $enrollment->courseSession->course->year,
    //             'semester' => $enrollment->courseSession->course->semester,
    //             'max_final_term_marks' => $enrollment->max_final_term_marks,
    //             'grade' => $gradeDetails['grade'],
    //             'gpa' => $gradeDetails['gpa'],
    //             'remark' => $gradeDetails['remark'],
    //         ];
    //     });

    //     return response()->json([
    //         'courses' => $response
    //     ], 200);
    // }

    //     public function showFullResult($year, $semester)
    // {
    //     $user = Auth::id();
    //     if (!$user) {
    //         return response()->json(['message' => 'User not authenticated'], 401);
    //     }

    //     $maxResults = Enrollment::where('student_id',  $user)
    //         ->whereHas('courseSession.course', function ($query) use ($year, $semester) {
    //             $query->where('year', $year);
    //             if (is_array($semester)) {
    //                 $query->whereIn('semester', $semester);
    //             } else {
    //                 $query->where('semester', $semester);
    //             }
    //         })
    //         ->with(['courseSession.course'])
    //         ->selectRaw('courseSession_id, MAX(final_term_marks) as max_final_term_marks')
    //         ->groupBy('courseSession_id')
    //         ->get();

    //     if ($maxResults->isEmpty()) {
    //         return response()->json(['message' => 'No results found for this student in this semester and year'], 404);
    //     }

    //     $totalCredits = 0;
    //     $weightedGpaSum = 0;

    //     $response = $maxResults->map(function ($enrollment) use (&$totalCredits, &$weightedGpaSum) {
    //         $gradeDetails = GradeHelper::getGrade($enrollment->max_final_term_marks);
    //         $credit = $enrollment->courseSession->course->credit;
    //         $gpa = $gradeDetails['gpa'];

    //         // Update cumulative credit and weighted GPA sum
    //         $totalCredits += $credit;
    //         $weightedGpaSum += ($credit * $gpa);

    //         return [
    //             'course_id' => $enrollment->courseSession->course->id,
    //             'course_name' => $enrollment->courseSession->course->name,
    //             'year' => $enrollment->courseSession->course->year,
    //             'semester' => $enrollment->courseSession->course->semester,
    //             'credit' => $credit,
    //             'max_final_term_marks' => $enrollment->max_final_term_marks,
    //             'grade' => $gradeDetails['grade'],
    //             'gpa' => $gpa,
    //             'remark' => $gradeDetails['remark'],
    //         ];
    //     });

    //     // Calculate CGPA
    //     $cgpa = ($totalCredits > 0) ? round($weightedGpaSum / $totalCredits, 2) : 0;

    //     return response()->json([
    //         'courses' => $response,
    //         'cgpa' => $cgpa
    //     ], 200);
    // }

    public function showFullResult($year, $semester)
    {
        $user = Auth::id();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Fetch the maximum final term marks for each course session
        $maxResults = Enrollment::where('student_id',  $user)
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

        // Fetch credit hours for all courses
        $courseCredits = Course::pluck('credit', 'id')->toArray(); // Get credit hours as an associative array [course_id => credit]

        $totalWeightedGPA = 0;
        $totalCreditHours = 0;

        // Map results and calculate CGPA
        $response = $maxResults->map(function ($enrollment) use ($courseCredits, &$totalWeightedGPA, &$totalCreditHours) {
            $gradeDetails = GradeHelper::getGrade($enrollment->max_final_term_marks);
            $courseId = $enrollment->courseSession->course->id;
            $creditHours = $courseCredits[$courseId] ?? 0; // Get credit hours for the course

            // Calculate weighted GPA for this course
            $weightedGPA = $gradeDetails['gpa'] * $creditHours;

            // Update total weighted GPA and credit hours
            $totalWeightedGPA += $weightedGPA;
            $totalCreditHours += $creditHours;

            return [
                'course_id' => $courseId,
                'course_name' => $enrollment->courseSession->course->name,
                'year' => $enrollment->courseSession->course->year,
                'semester' => $enrollment->courseSession->course->semester,
                'max_final_term_marks' => $enrollment->max_final_term_marks,
                'grade' => $gradeDetails['grade'],
                'gpa' => $gradeDetails['gpa'],
                'remark' => $gradeDetails['remark'],
                'credit_hours' => $creditHours, // Add credit hours to the response
            ];
        });

        // Calculate CGPA
        $cgpa = $totalCreditHours > 0 ? $totalWeightedGPA / $totalCreditHours : 0;

        return response()->json([
            'courses' => $response,
            'total_cgpa' => $cgpa, // Add CGPA to the response
        ], 200);
    }
}
