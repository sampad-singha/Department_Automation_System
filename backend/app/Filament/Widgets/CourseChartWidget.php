<?php

namespace App\Filament\Widgets;

use App\Models\CourseSession;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Illuminate\Support\Facades\DB;

class CourseChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Course Sessions per Month';
    protected static string $color = 'primary';

    protected function getFilters(): ?array
    {
        $currentYear = now()->year;
        $years = range($currentYear, $currentYear - 5);

        return collect($years)->mapWithKeys(fn($year) => [$year => (string) $year])->toArray();
    }

    protected function getData(): array
    {
        $year = $this->filter ?? now()->year;

        $sessions = CourseSession::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('F', mktime(0, 0, 0, $i, 10));
            $data[] = $sessions[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => "Course Sessions in $year",
                    'data' => $data,
                    'fill' => false,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
