<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseMaterialResource\Pages;
use App\Models\CourseResource;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourseMaterialResource extends Resource
{
    protected static ?string $model = CourseResource::class;
    protected static ?string $label = 'Course Resources';
    protected static ?string $navigationGroup = 'Course Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('title')
                ->searchable(),

            Tables\Columns\TextColumn::make('courseSession.course.name')
                ->label('Course')
                ->searchable(),

            Tables\Columns\TextColumn::make('uploadedBy.name')
                ->label('Uploaded By')
                ->searchable(),

            Tables\Columns\TextColumn::make('file_name')
                ->label('File Name')
                ->searchable(),

            Tables\Columns\TextColumn::make('file_type')
                ->label('File Type')
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Uploaded At')
                ->dateTime()
                ->sortable(),
        ])
            ->filters([])
            ->actions([
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
            'index' => Pages\ListCourseMaterials::route('/'),
        ];
    }
}
