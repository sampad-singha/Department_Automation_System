<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers\UsersRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique('roles', 'name', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('guard_name')
                    ->label('Guard Name')
                    ->options([
                        'web' => 'Web',
                        'api' => 'API',
                    ])
                    ->default('web'),
                Forms\Components\CheckboxList::make('permissions')
                    ->label('Permissions')
                    ->relationship('permissions', 'name')
//                    ->options(function () {
//                        return \Spatie\Permission\Models\Permission::query()
//                            ->orderBy('category')
//                            ->orderBy('name')
//                            ->get()
//                            ->groupBy('category')
//                            ->mapWithKeys(fn ($permissions, $category) => [
//                                $category => $permissions->mapWithKeys(fn ($p) => [$p->id => $p->name])
//                            ]);
//                    })
                    ->columns(3)
                    ->columnSpanFull()
                    ->bulkToggleable(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guard_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('permissions.name')
                    ->formatStateUsing(function ($state, $record) {
                        $permissions = $record->permissions->pluck('name')->toArray();

                        if (count($permissions) > 2) {
                            return implode(', ', array_slice($permissions, 0, 2)) . ' and ' . (count($permissions) - 2) . ' more';
                        }

                        return implode(', ', $permissions);
                    })
                    ->searchable(),
            ])
            ->filters([
                //
            ])
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
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
            'view' => Pages\ViewRole::route('/{record}'),
        ];
    }
}
