<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Filament\Resources\ApplicationResource;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

class PendingApplicationWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 1;
    protected function getStats(): array
    {
        $pendingCount = Application::whereNull('authorized_by')->count();

        return [
            Stat::make('Applications', $pendingCount)
                ->url(ApplicationResource::getUrl())
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => 'redirectToApplications',
                ])
                ->icon('heroicon-o-document')
                ->backgroundColor('gray')
                ->iconPosition('end')
                ->iconColor('warning'),
        ];
    }

    public function redirectToApplications(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->to(ApplicationResource::getUrl());
    }
}
