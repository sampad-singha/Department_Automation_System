<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use App\Models\Enrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_session_id')
                    ->relationship('courseSession', 'id') // Use direct ID relationship
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->course->name)
                    ->required()
                    ->disabled(),
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required()
                    ->disabled(),
                Forms\Components\Placeholder::make('student_session')
                    ->label('Student Session')
                    ->content(fn ($record) => $record->student?->session ?? 'N/A'),

                Forms\Components\Toggle::make('is_enrolled')
                    ->default(false)
                    ->disabled(),
                Forms\Components\TextInput::make('class_assessment_marks')
                    ->numeric()
                    ->maxValue(30)
                    ->required(),
                Forms\Components\TextInput::make('final_term_marks')
                    ->numeric()
                    ->maxValue(70)
                    ->required(),
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
                    ->sortable()
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
