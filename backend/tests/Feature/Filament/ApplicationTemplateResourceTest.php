<?php

use App\Filament\Resources\ApplicationTemplateResource;
use App\Filament\Resources\ApplicationTemplateResource\Pages\CreateApplicationTemplate;
use App\Filament\Resources\ApplicationTemplateResource\Pages\EditApplicationTemplate;
use App\Filament\Resources\ApplicationTemplateResource\Pages\ListApplicationTemplates;
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
});

it('can list application templates', function () {
    // Create templates using standard create method
    $template1 = ApplicationTemplate::create([
        'title' => 'Leave Form',
        'type' => 'leave',
        'body' => 'Leave content'
    ]);

    $template2 = ApplicationTemplate::create([
        'title' => 'Medical Form',
        'type' => 'medical',
        'body' => 'Medical content'
    ]);

    Livewire::test(ListApplicationTemplates::class)
        ->assertCanSeeTableRecords([$template1, $template2]);
});

it('can create an application template', function () {
    Livewire::test(CreateApplicationTemplate::class)
        ->fillForm([
            'title' => 'New Template',
            'type' => 'generic',
            'body' => 'Template content with %placeholder%',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(ApplicationTemplate::class, [
        'title' => 'New Template',
        'type' => 'generic',
    ]);
});

it('requires required fields when creating', function () {
    Livewire::test(CreateApplicationTemplate::class)
        ->fillForm([
            'title' => '',
            'type' => '',
            'body' => '',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'required',
            'type' => 'required',
            'body' => 'required',
        ]);
});

it('can edit an application template', function () {
    $template = ApplicationTemplate::create([
        'title' => 'Old Title',
        'type' => 'old-type',
        'body' => 'Old content'
    ]);

    Livewire::test(EditApplicationTemplate::class, ['record' => $template->id])
        ->fillForm([
            'title' => 'Updated Title',
            'type' => 'new-type',
            'body' => 'Updated content'
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $template->refresh();
    expect($template->title)->toBe('Updated Title')
        ->and($template->type)->toBe('new-type');
});

it('validates max lengths', function () {
    $longString = str_repeat('a', 256);
    $longBody = str_repeat('a', 10001);

    Livewire::test(CreateApplicationTemplate::class)
        ->fillForm([
            'title' => $longString,
            'type' => $longString,
            'body' => $longBody,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'max',
            'type' => 'max',
            'body' => 'max',
        ]);
});

it('shows correct columns in table', function () {
    ApplicationTemplate::create(['title' => 'Test', 'type' => 'test', 'body' => 'content']);

    Livewire::test(ListApplicationTemplates::class)
        ->assertTableColumnExists('id')
        ->assertTableColumnExists('title')
        ->assertTableColumnExists('type')
        ->assertTableColumnExists('created_at');
});

it('uses correct model and navigation icon', function () {
    $resource = new ApplicationTemplateResource();

    expect($resource->getModel())->toBe(ApplicationTemplate::class)
        ->and($resource->getNavigationIcon())->toBe('heroicon-o-rectangle-stack');
});
