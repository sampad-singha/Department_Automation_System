<?php

namespace App\Filament\Widgets;

use App\Models\User;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

class AdvancedStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        //show in thousands
        if ($totalUsers > 1000) {
            $totalUsers = number_format($totalUsers / 1000, 1) . 'k';
        }
        $totalTeachers = (new \App\Models\User)->role('teacher')->count();
        //show in thousands
        if ($totalTeachers > 1000) {
            $totalTeachers = number_format($totalTeachers / 1000, 1) . 'k';
        }
        $totalStudents =  (new \App\Models\User)->role('student')->count();
        //show in thousands
        if ($totalStudents > 1000) {
            $totalStudents = number_format($totalStudents / 1000, 1) . 'k';
        }
        return [
            Stat::make('Total Users', $totalUsers)
                ->icon('heroicon-o-user')
                ->backgroundColor('gray')
                ->iconPosition('end')
                ->iconColor('success')
                ->textColor('gray', 'primary', 'info'),

            Stat::make('Total Teachers', $totalTeachers)
                ->icon('heroicon-o-academic-cap')
                ->iconColor('warning'),

            Stat::make('Total Students', $totalStudents)
                ->icon('heroicon-o-user-group')
                ->iconColor('danger'),
        ];
    }
}
