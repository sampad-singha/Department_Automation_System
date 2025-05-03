<?php

namespace App\Filament\Widgets;

use App\Models\CourseSession;
use App\Models\User;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Illuminate\Support\Facades\DB;

class TeacherCourseChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Ongoing Course Sessions per Teacher';

    protected function getData(): array
    {
        $sessions = CourseSession::select('teacher_id', DB::raw('COUNT(*) as count'))
            ->where('status', 'ongoing')
            ->groupBy('teacher_id')
            ->get();

        $labels = [];
        $data = [];
        $colors = [];

        $colorPalette = [
            '#FF6384', // Soft Red
            '#36A2EB', // Soft Blue
            '#FFCE56', // Soft Yellow
            '#4BC0C0', // Teal
            '#9966FF', // Purple
            '#FF9F40', // Orange
            '#C9CBCF', // Gray
            '#FFCD56', // Light Yellow
            '#4D5360', // Dark Gray
            '#AC64AD', // Lavender
        ];

        foreach ($sessions as $index => $session) {
            $teacher = User::find($session->teacher_id);
            $labels[] = $teacher ? $teacher->name : 'Unknown';
            $data[] = $session->count;
            $colors[] = $colorPalette[$index % count($colorPalette)];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ongoing Sessions per Teacher',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0, // Removes the border around each bar
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'barThickness' => 20, // Adjust as needed
                    'grid' => [
                        'drawBorder' => false, // Removes the border on the x-axis
                    ],
                ],
                'y' => [
                    'grid' => [
                        'drawBorder' => false, // Removes the border on the y-axis
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
