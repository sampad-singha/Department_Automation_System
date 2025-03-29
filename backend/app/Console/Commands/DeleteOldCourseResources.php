<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CourseSession;
use App\Models\CourseResource;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command as CommandAlias;

class DeleteOldCourseResources extends Command
{
    // Command name to run from terminal
    protected $signature = 'course-resources:cleanup';

    // Command description
    protected $description = 'Deletes course session files after 30 seconds if the session is completed';

    public function handle(): int
    {
        $threshold = Carbon::now()->subSeconds(30);

        $sessions = CourseSession::where('status', 'completed')
            ->where('updated_at', '<', $threshold)
            ->get();

        foreach ($sessions as $session) {
            $resources = CourseResource::where('course_session_id', $session->id)->get();

            foreach ($resources as $resource) {
                Storage::delete($resource->file_path);
                $resource->delete();
            }

            $this->info("Deleted resources for course session ID: {$session->id}");
        }

        return CommandAlias::SUCCESS;
    }
}
