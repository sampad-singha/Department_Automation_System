<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Imports\UsersImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('ImportStudents ')
                ->label('Import Students')
                ->color('danger')
                ->icon('heroicon-s-document-arrow-up')
            ->form([
                FileUpload::make('attachment')
                    ->label('Attachment')
                    ->rules('required', 'mimes:csv,xlsx'),
            ])
            ->action(function (array $data) {
                $file = public_path('storage/' . $data['attachment']);

                try {
                    Excel::import(new UsersImport, $file);
                }
                catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with('error', 'Error importing file');
                }
            }),

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
