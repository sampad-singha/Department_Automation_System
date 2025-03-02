<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EnrollmentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view any enrollments');
    }

//    public function view(Enrollment $enrollment): bool
//    {
//        $id = $enrollment->student_id;
//        return $id === Auth::user()->id;
//    }

    public function create(User $user): bool
    {
        return $user->can('create enrollments');
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        // Retrieve the associated CourseSession
        $courseSession = $enrollment->courseSession;
        return ($user->id === $courseSession->teacher_id) && ($user->can('update enrollments'));
    }

    public function delete(User $user): bool
    {
        return $user->can('delete enrollments');
    }

    public function restore(User $user): bool
    {
        return $user->can('restore enrollments');
    }

    public function forceDelete(User $user): bool
    {
        return $user->can('force delete enrollments');
    }
}
