<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\CourseSession;
use App\Models\Enrollment;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationGroup = 'Course Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('course_code')
                    ->label('Course Code')
                    ->content(fn ($record) => $record->courseSession?->course?->code ?? 'N/A'),
                Placeholder::make('course_session')
                    ->label('Course Title')
                    ->content(fn ($record) => $record->courseSession?->course?->name ?? 'N/A'),
                Placeholder::make('course_year')
                    ->label('Year')
                    ->content(fn ($record) => $record->courseSession?->course?->year ?? 'N/A'),
                Placeholder::make('course_semester')
                    ->label('Semester')
                    ->content(fn ($record) => $record->courseSession?->course?->semester ?? 'N/A'),
                Placeholder::make('course_session')
                    ->label('Session')
                    ->content(function ($record) {
                        return $record?->courseSession?->session ?? 'N/A';
                    }),
                Placeholder::make('teacher')
                    ->label('Teacher')
                    ->content(fn ($record) => $record->courseSession?->teacher?->name ?? 'N/A'),
                Placeholder::make('student')
                    ->label('Student')
                    ->content(fn ($record) => $record->student?->name ?? 'N/A'),
                Placeholder::make('student_session')
                    ->label('Student Session')
                    ->content(fn ($record) => $record->student?->session ?? 'N/A'),
                TextInput::make('class_assessment_marks')
                    ->numeric()
                    ->maxValue(30)
                    ->required(),
                TextInput::make('final_term_marks')
                    ->numeric()
                    ->maxValue(70)
                    ->required(),
                Toggle::make('is_enrolled')
                    ->default(false)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('courseSession.course.name')
                    ->label('Course Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('courseSession.course.code')
                    ->label('Course Code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('courseSession.session')
                    ->label('Course Session')
                    ->searchable(),

                Tables\Columns\TextColumn::make('courseSession.course.year')
                    ->label('Year')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('courseSession.course.semester')
                    ->label('Semester')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.session')
                    ->label('Student Session')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_enrolled')
                    ->label('Enrollment Status')
                    ->searchable()
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('class_assessment_marks')
                    ->label('CA Marks')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_term_marks')
                    ->label('Final Term Marks')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enrolled At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('course_session')
                    ->label('Course Session')
                    ->relationship('courseSession', 'session')
                    ->options(function () {
                        return CourseSession::all()->pluck('name', 'id');
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'view' => Pages\ViewEnrollment::route('/{record}'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }
}
