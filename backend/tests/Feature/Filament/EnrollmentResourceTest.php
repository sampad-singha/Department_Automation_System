<?php

use App\Filament\Resources\EnrollmentResource;
use App\Filament\Resources\EnrollmentResource\Pages\CreateEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\EditEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\ListEnrollments;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\Enrollment;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    // Create admin user
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    actingAs($admin);

    // Create teacher and course session
    $this->teacher = User::factory()->create()->assignRole('teacher');
    $this->course = Course::factory()->create();
    $this->courseSession = CourseSession::factory()->create([
        'course_id' => $this->course->id,
        'teacher_id' => $this->teacher->id
    ]);

    // Create student
    $this->student = User::factory()->create()->assignRole('student');
});

it('can list enrollments', function () {
    $enrollments = Enrollment::factory()
        ->count(3)
        ->create([
            'courseSession_id' => $this->courseSession->id,
            'student_id' => $this->student->id
        ]);

    Livewire::test(ListEnrollments::class)
        ->assertCanSeeTableRecords($enrollments);
});


it('can edit an enrollment', function () {
    $enrollment = Enrollment::factory()->create([
        'courseSession_id' => $this->courseSession->id,
        'student_id' => $this->student->id
    ]);

    Livewire::test(EditEnrollment::class, ['record' => $enrollment->id])
        ->fillForm([
            'class_assessment_marks' => 28,
            'final_term_marks' => 68
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $enrollment->refresh();
    expect($enrollment->class_assessment_marks)->toBe(28)
        ->and($enrollment->final_term_marks)->toBe(68);
});

it('validates mark fields', function () {
    // Test max values and required fields
    Livewire::test(CreateEnrollment::class)
        ->fillForm([
            'class_assessment_marks' => 35,
            'final_term_marks' => 75
        ])
        ->call('create')
        ->assertHasFormErrors([
            'class_assessment_marks' => 'max',
            'final_term_marks' => 'max'
        ]);
});

it('filters by enrollment status', function () {
    $enrolled = Enrollment::factory()->create(['is_enrolled' => true]);
    $notEnrolled = Enrollment::factory()->create(['is_enrolled' => false]);

    Livewire::test(ListEnrollments::class)
        ->filterTable('is_enrolled', 'true')
        ->assertCanSeeTableRecords([$enrolled])
        ->assertCanNotSeeTableRecords([$notEnrolled]);
});

it('filters by course', function () {
    $anotherCourse = Course::factory()->create();
    $anotherSession = CourseSession::factory()->create(['course_id' => $anotherCourse->id]);
    $anotherEnrollment = Enrollment::factory()->create(['courseSession_id' => $anotherSession->id]);

    Livewire::test(ListEnrollments::class)
        ->filterTable('course_id', $this->course->id)
        ->assertCanSeeTableRecords(Enrollment::whereHas('courseSession', fn($q) => $q->where('course_id', $this->course->id))->get())
        ->assertCanNotSeeTableRecords([$anotherEnrollment]);
});

it('shows correct columns in table', function () {
    Enrollment::factory()->create();

    Livewire::test(ListEnrollments::class)
        ->assertTableColumnExists('courseSession.course.name')
        ->assertTableColumnExists('courseSession.course.code')
        ->assertTableColumnExists('student.name')
        ->assertTableColumnExists('class_assessment_marks')
        ->assertTableColumnExists('final_term_marks')
        ->assertTableColumnExists('is_enrolled');
});

it('uses correct model and navigation settings', function () {
    $resource = new EnrollmentResource();

    expect($resource->getModel())->toBe(Enrollment::class)
        ->and($resource->getNavigationIcon())->toBe('heroicon-o-rectangle-stack')
        ->and($resource->getNavigationGroup())->toBe('Course Management');
});
