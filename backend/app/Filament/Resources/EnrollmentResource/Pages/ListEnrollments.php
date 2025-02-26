<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        // Retrieve distinct year and semester combinations from the Course model, sorted by year and semester
        $combinations = DB::table('courses')
            ->select('year', 'semester')
            ->distinct()
            ->orderBy('year')
            ->orderBy('semester')
            ->get();

        // Initialize tabs array with an 'All Enrollments' tab
        $tabs = [
            'all' => Tab::make('All Semesters')
                ->modifyQueryUsing(fn (Builder $query) => $query),
        ];

        // Iterate over each combination to create corresponding tabs
        foreach ($combinations as $combination) {
            $tabKey = "year_{$combination->year}_semester_{$combination->semester}";
            $tabLabel = "{$combination->year}-{$combination->semester}";

            $tabs[$tabKey] = Tab::make($tabLabel)
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('course', function (Builder $query) use ($combination) {
                    $query->where('year', $combination->year)
                        ->where('semester', $combination->semester);
                }));
        }

        return $tabs;
    }
}
