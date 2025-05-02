<?php

use App\Filament\Resources\ApplicationResource;
use App\Filament\Resources\ApplicationResource\Pages\EditApplication;
use App\Filament\Resources\ApplicationResource\Pages\ListApplications;
use App\Models\Application;
use App\Models\ApplicationTemplate;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    actingAs($admin);

    $this->teacher = User::factory()->create()->assignRole('teacher');
    $this->student = User::factory()->create();
    $this->template = ApplicationTemplate::create([
        'title' => 'Leave Form',
        'type' => 'leave',
        'body' => 'Template content'
    ]);
});

it('can list applications', function () {
    $app1 = Application::create([
        'user_id' => $this->student->id,
        'application_template_id' => $this->template->id,
        'body' => 'Content 1',
        'status' => 'pending'
    ]);

    $app2 = Application::create([
        'user_id' => $this->student->id,
        'application_template_id' => $this->template->id,
        'body' => 'Content 2',
        'status' => 'approved'
    ]);

    Livewire::test(ListApplications::class)
        ->assertCanSeeTableRecords([$app1, $app2]);
});

it('can edit application authorization', function () {
    $application = Application::create([
        'user_id' => $this->student->id,
        'application_template_id' => $this->template->id,
        'body' => 'Test content',
        'status' => 'pending'
    ]);

    Livewire::test(EditApplication::class, ['record' => $application->id])
        ->fillForm([
            'authorized_by' => $this->teacher->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $application->refresh();
    expect($application->authorized_by)->toBe($this->teacher->id);
});

it('filters by status', function () {
    $pending = Application::create([
        'user_id' => $this->student->id,
        'application_template_id' => $this->template->id,
        'body' => 'Pending',
        'status' => 'pending'
    ]);

    $approved = Application::create([
        'user_id' => $this->student->id,
        'application_template_id' => $this->template->id,
        'body' => 'Approved',
        'status' => 'approved'
    ]);

    Livewire::test(ListApplications::class)
        ->filterTable('status', 'approved')
        ->assertCanSeeTableRecords([$approved])
        ->assertCanNotSeeTableRecords([$pending]);
});

it('filters by authorization status', function () {
    $assigned = Application::create([
        'user_id' => $this->student->id,
        'application_template_id' => $this->template->id,
        'body' => 'Assigned',
        'authorized_by' => $this->teacher->id,
        'status' => 'pending'
    ]);

    $unassigned = Application::create([
        'user_id' => $this->student->id,
        'application_template_id' => $this->template->id,
        'body' => 'Unassigned',
        'status' => 'pending'
    ]);

    Livewire::test(ListApplications::class)
        ->filterTable('authorized_status', 'assigned')
        ->assertCanSeeTableRecords([$assigned])
        ->assertCanNotSeeTableRecords([$unassigned]);
});

it('shows correct columns in table', function () {
    Application::create([
        'user_id' => $this->student->id,
        'application_template_id' => $this->template->id,
        'body' => 'Test content',
        'status' => 'pending'
    ]);

    Livewire::test(ListApplications::class)
        ->assertTableColumnExists('user.name')
        ->assertTableColumnExists('applicationTemplate.title')
        ->assertTableColumnExists('status')
        ->assertTableColumnExists('created_at')
        ->assertTableColumnExists('authorizedBy.name');
});

it('uses correct model and navigation icon', function () {
    $resource = new ApplicationResource();

    expect($resource->getModel())->toBe(Application::class)
        ->and($resource->getNavigationIcon())->toBe('heroicon-o-rectangle-stack');
});
