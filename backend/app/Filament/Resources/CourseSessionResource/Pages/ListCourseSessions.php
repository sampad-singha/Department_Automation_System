<?php

namespace App\Filament\Resources\CourseSessionResource\Pages;

use App\Filament\Resources\CourseSessionResource;
use App\Http\Controllers\api\EnrollmentController;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ListCourseSessions extends ListRecords
{
    protected static string $resource = CourseSessionResource::class;

    // Convert this to a static method so it can be reused
    public static function getCoursesFor($year, $semester): array
    {
        if (!$year || !$semester) {
            return [];
        }

        return Course::where('year', $year)
            ->where('semester', $semester)
            ->get()
            ->map(function ($course) {
                return [
                    'course_id' => $course->id,
                    'course_name' => $course->name,
                    'teacher_id' => null,
                ];
            })
            ->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('Bulk Create')
                ->label('Bulk Create Course Sessions')
                ->icon('heroicon-o-plus')
                ->form([
                    Forms\Components\TextInput::make('session')
                        ->label('Session')
                        ->required(),

                    Forms\Components\Select::make('year')
                        ->label('Year')
                        ->options([
                            1 => 'Year 1',
                            2 => 'Year 2',
                            3 => 'Year 3',
                            4 => 'Year 4',
                        ])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $set('courseAssignments', self::getCoursesFor($state, $get('semester')));
                        }),

                    Forms\Components\Select::make('semester')
                        ->label('Semester')
                        ->options([
                            1 => 'Semester 1',
                            2 => 'Semester 2',
                        ])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $set('courseAssignments', self::getCoursesFor($get('year'), $state));
                        }),

                    Forms\Components\Repeater::make('courseAssignments')
                        ->label('Courses')
                        ->schema([
                            Forms\Components\Hidden::make('course_id'),
                            Forms\Components\TextInput::make('course_name')->disabled(),
                            Forms\Components\Select::make('teacher_id')
                                ->label('Assign Teacher')
                                ->options(User::role('teacher')->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->addable(false)
                        ->deletable(false),
                ])
                ->action(function (array $data) {
                    foreach ($data['courseAssignments'] as $assignment) {
                        $existingSession = CourseSession::where('course_id', $assignment['course_id'])
                            ->where('session', $data['session'])
                            ->first();

                        if (!$existingSession) {
                            $newSession = CourseSession::create([
                                'course_id' => $assignment['course_id'],
                                'session' => $data['session'],
                                'teacher_id' => $assignment['teacher_id'],
                            ]);
                            app(EnrollmentController::class)->store_all($newSession);
                        } else {
                            $existingSession->update(['teacher_id' => $assignment['teacher_id']]);
                        }
                    }
                }),
        ];
    }

    public function getTabs(): array
    {
        $combinations = DB::table('courses')
            ->select('year', 'semester')
            ->distinct()
            ->orderBy('year')
            ->orderBy('semester')
            ->get();

        $tabs = [
            'all' => Tab::make('All Semesters')
                ->modifyQueryUsing(fn (Builder $query) => $query),
        ];

        foreach ($combinations as $combination) {
            $tabKey = "year_{$combination->year}_semester_$combination->semester";
            $tabLabel = "Y$combination->year-S$combination->semester";

            $tabs[$tabKey] = Tab::make($tabLabel)
                ->modifyQueryUsing(function (Builder $query) use ($combination) {
                    $courseIds = Course::where('year', $combination->year)
                        ->where('semester', $combination->semester)
                        ->pluck('id');

                    $query->whereIn('course_id', $courseIds);
                });
        }

        return $tabs;
    }
}
