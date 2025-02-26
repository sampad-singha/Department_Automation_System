<?php

namespace App\Filament\Resources\CourseSessionResource\Pages;

use App\Filament\Resources\CourseSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListCourseSessions extends ListRecords
{
    protected static string $resource = CourseSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        // Retrieve distinct year and semester combinations
        $combinations = DB::table('courses')
            ->select('year', 'semester')
            ->distinct()
            ->orderBy('year')
            ->orderBy('semester')
            ->get();

        // Initialize tabs array with an 'All Sessions' tab
        $tabs = [
            'all' => Tab::make('All Semesters')
                ->modifyQueryUsing(fn (Builder $query) => $query),
        ];

        // Iterate over each combination to create corresponding tabs
        foreach ($combinations as $combination) {
            $tabKey = "year_{$combination->year}_semester_{$combination->semester}";
            $tabLabel = "{$combination->year}-{$combination->semester}";

            $tabs[$tabKey] = Tab::make($tabLabel)
                ->modifyQueryUsing(fn (Builder $query) => $query->where([
                    'year' => $combination->year,
                    'semester' => $combination->semester,
                ]));
        }

        return $tabs;
    }
}
