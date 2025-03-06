<?php

namespace App\Helpers;

class GradeHelper
{
    public static function getGrade($marks)
    {
        if ($marks >= 80) {
            return ['grade' => 'A+', 'gpa' => 4.00, 'remark' => 'Outstanding'];
        } elseif ($marks >= 75) {
            return ['grade' => 'A', 'gpa' => 3.75, 'remark' => 'Excellent'];
        } elseif ($marks >= 70) {
            return ['grade' => 'A-', 'gpa' => 3.50, 'remark' => 'Very Good'];
        } elseif ($marks >= 65) {
            return ['grade' => 'B+', 'gpa' => 3.25, 'remark' => 'Good'];
        } elseif ($marks >= 60) {
            return ['grade' => 'B', 'gpa' => 3.00, 'remark' => 'Satisfactory'];
        } elseif ($marks >= 55) {
            return ['grade' => 'B-', 'gpa' => 2.75, 'remark' => 'Below Satisfactory'];
        } elseif ($marks >= 50) {
            return ['grade' => 'C+', 'gpa' => 2.50, 'remark' => 'Average'];
        } elseif ($marks >= 45) {
            return ['grade' => 'C', 'gpa' => 2.25, 'remark' => 'Pass'];
        } elseif ($marks >= 40) {
            return ['grade' => 'D', 'gpa' => 2.00, 'remark' => 'Poor'];
        } else {
            return ['grade' => 'F', 'gpa' => 0.00, 'remark' => 'Fail'];
        }
    }
}
