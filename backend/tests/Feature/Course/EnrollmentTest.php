<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\CourseSession;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

const ENROLLMENT_ENDPOINT = '/api/courses/active/enroll';
const MARKS_ENDPOINT = '/api/courses/active/enrollments/updateMarks';

beforeEach(function () {
    // Create roles
    Role::create(['name' => 'student']);
    Role::create(['name' => 'teacher']);

    // Create department
    $this->department = Department::factory()->create([
        'short_name' => 'CS',
        'name' => 'Computer Science'
    ]);

    // Create course with specific academic requirements
    $this->course = Course::factory()->create([
        'department_id' => $this->department->id,
        'year' => 3,
        'semester' => 1,
        'code' => 'CS101',
        'name' => 'Programming Fundamentals'
    ]);

    // Create teacher
    $this->teacher = User::factory()->create([
        'department_id' => $this->department->id
    ])->assignRole('teacher');

    // Create properly qualified student
    $this->student = User::factory()->create([
        'department_id' => $this->department->id,
        'year' => $this->course->year,
        'semester' => $this->course->semester
    ])->assignRole('student');

    // Create current session
    $this->courseSession = CourseSession::factory()->create([
        'teacher_id' => $this->teacher->id,
        'course_id' => $this->course->id,
        'session' => (string) now()->year
    ]);
});


// Prevent Non-Student Enrollment
it('prevents non-students from enrolling', function () {
    $regularUser = User::factory()->create();

    $response = $this->actingAs($regularUser)
        ->postJson(ENROLLMENT_ENDPOINT, [
            'course_id' => $this->course->id
        ]);

    $response->assertStatus(403)
        ->assertJson([
            'status' => 'error',
            'message' => 'Only students can enroll in courses.'
        ]);
});

// Prevent Duplicate Enrollment
it('prevents duplicate enrollments in same session', function () {
    Enrollment::factory()->create([
        'student_id' => $this->student->id,
        'courseSession_id' => $this->courseSession->id
    ]);

    $response = $this->actingAs($this->student)
        ->postJson(ENROLLMENT_ENDPOINT, [
            'course_id' => $this->course->id
        ]);

    $response->assertStatus(403)
        ->assertJson([
            'status' => 'error',
            'message' => 'You are not eligible to retake this course.'
        ]);
});

// 4. Teacher Marks Update
it('allows authorized teacher to update marks', function () {
    $enrollment = Enrollment::factory()->create([
        'courseSession_id' => $this->courseSession->id,
        'student_id' => $this->student->id
    ]);

    $response = $this->actingAs($this->teacher)
        ->postJson(MARKS_ENDPOINT, [
            'courseSession_id' => $this->courseSession->id,
            'enrollments' => [
                [
                    'id' => $enrollment->id,
                    'class_assessment_marks' => 25,
                    'final_term_marks' => 65
                ]
            ]
        ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Enrollments updated successfully.']);
});

// 5. Prevent Unauthorized Marks Update
it('prevents unauthorized mark updates', function () {
    $otherTeacher = User::factory()->create()->assignRole('teacher');
    $otherSession = CourseSession::factory()->create(['teacher_id' => $otherTeacher->id]);
    $enrollment = Enrollment::factory()->create(['courseSession_id' => $otherSession->id]);

    $response = $this->actingAs($this->teacher)
        ->postJson(MARKS_ENDPOINT, [
            'courseSession_id' => $this->courseSession->id,
            'enrollments' => [
                [
                    'id' => $enrollment->id,
                    'class_assessment_marks' => 25,
                    'final_term_marks' => 65
                ]
            ]
        ]);

    $response->assertStatus(403);
});

// 8. Marks Validation
it('validates mark input ranges', function () {
    $enrollment = Enrollment::factory()->create([
        'courseSession_id' => $this->courseSession->id
    ]);

    $response = $this->actingAs($this->teacher)
        ->postJson(MARKS_ENDPOINT, [
            'courseSession_id' => $this->courseSession->id,
            'enrollments' => [
                [
                    'id' => $enrollment->id,
                    'class_assessment_marks' => 35,
                    'final_term_marks' => 80
                ]
            ]
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'enrollments.0.class_assessment_marks',
            'enrollments.0.final_term_marks'
        ]);
});

//// 9. Server Error Handling
//it('handles server errors during enrollment', function () {
//    \Illuminate\Support\Facades\DB::shouldReceive('beginTransaction')
//        ->andThrow(new \RuntimeException('Database error'));
//
//    $response = $this->actingAs($this->student)
//        ->postJson(ENROLLMENT_ENDPOINT, [
//            'course_id' => $this->course->id
//        ]);
//
//    $response->assertStatus(500)
//        ->assertJsonStructure(['error']);
//});

// 10. Academic Year Validation
it('prevents enrollment for mismatched academic year', function () {
    $wrongStudent = User::factory()->create([
        'department_id' => $this->department->id,
        'year' => $this->course->year + 1,
        'semester' => $this->course->semester
    ])->assignRole('student');

    $response = $this->actingAs($wrongStudent)
        ->postJson(ENROLLMENT_ENDPOINT, [
            'course_id' => $this->course->id
        ]);

    $response->assertStatus(403);
});

// 11. Semester Validation
it('prevents enrollment for mismatched semester', function () {
    $wrongStudent = User::factory()->create([
        'department_id' => $this->department->id,
        'year' => $this->course->year,
        'semester' => $this->course->semester % 2 + 1
    ])->assignRole('student');

    $response = $this->actingAs($wrongStudent)
        ->postJson(ENROLLMENT_ENDPOINT, [
            'course_id' => $this->course->id
        ]);

    $response->assertStatus(403);
});
