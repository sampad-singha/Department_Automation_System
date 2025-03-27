<?php

namespace App\Http\Controllers\api;

use App\Models\Enrollment;
use App\Helpers\GradeHelper;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function showResult($courseId)
    {
        $user = Auth::user();
        try {
            // Subquery to get only the highest-scoring enrollment per course session
            $enrollment = Enrollment::with(['courseSession.course'])
                ->where('student_id', $user->id)
                ->whereHas('courseSession', function ($query) use ($courseId) {
                    $query->where('course_id', $courseId);
                })
                ->orderByDesc(\DB::raw('(class_assessment_marks + final_term_marks)'))
                ->first(); // ✅ Fetch only the single best enrollment

            $maxMarks = $enrollment->final_term_marks + $enrollment->class_assessment_marks;

            $gradeDetails = GradeHelper::getGrade($maxMarks);

            return response()->json([
                'course_id' => $courseId,
                'max_final_term_marks' => $maxMarks,
                'grade' => $gradeDetails['grade'],
                'gpa' => $gradeDetails['gpa'],
                'remark' => $gradeDetails['remark'],
                'user_id' => $user->id,
                'user_name' => $user->name,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching results'], 500);
        }
    }


    public function showFullResult($year, $semester)
    {
        $user = Auth::id();

        try {
            // Fetch enrollments with the highest (CA + Final Term) per course session
            $bestResults = Enrollment::where('student_id', $user)
                ->whereHas('courseSession.course', function ($query) use ($year, $semester) {
                    $query->where('year', $year);
                    if (is_array($semester)) {
                        $query->whereIn('semester', $semester);
                    } else {
                        $query->where('semester', $semester);
                    }
                })
                ->with(['courseSession.course'])
                ->selectRaw('*, (class_assessment_marks + final_term_marks) as total_marks')
                ->orderByDesc('total_marks') // Order by highest total marks
                ->get()
                ->unique('course_session_id'); // Keep only one per course session (best result)

            if ($bestResults->isEmpty()) {
                return response()->json(['message' => 'No results found for this student in this semester and year'], 404);
            }

            // Fetch credit hours for all courses
            $courseCredits = Course::pluck('credit', 'id')->toArray(); // Get credit hours as [course_id => credit]

            $totalWeightedGPA = 0;
            $totalCreditHours = 0;

            // Map results and calculate CGPA
            $response = $bestResults->map(function ($enrollment) use ($courseCredits, &$totalWeightedGPA, &$totalCreditHours) {
                // Compute total marks (CA + Final)
                $totalMarks = $enrollment->class_assessment_marks + $enrollment->final_term_marks;

                // Get grade based on total marks
                $gradeDetails = GradeHelper::getGrade($totalMarks);

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
                    'total_marks' => $totalMarks, // Include total marks in response
                    'class_assessment_marks' => $enrollment->class_assessment_marks,
                    'final_term_marks' => $enrollment->final_term_marks,
                    'grade' => $gradeDetails['grade'],
                    'gpa' => $gradeDetails['gpa'],
                    'remark' => $gradeDetails['remark'],
                    'credit_hours' => $creditHours,
                ];
            });

            // Calculate CGPA
            $cgpa = $totalCreditHours > 0 ? $totalWeightedGPA / $totalCreditHours : 0;

            return response()->json([
                'courses' => $response,
                'total_cgpa' => round($cgpa, 2), // Round CGPA for better readability
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching results'], 500);
        }
    }
}
