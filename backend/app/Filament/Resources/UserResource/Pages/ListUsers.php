<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users')
                ->modifyQueryUsing(fn (Builder $query) => $query),
            'students' => Tab::make('Students')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'student'))),
            'teachers' => Tab::make('Teachers')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'teacher'))),
            'admins' => Tab::make('Admins')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'admin'))),
            'superadmins' => Tab::make('Super Admins')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'super-admin'))),
        ];
    }
}
