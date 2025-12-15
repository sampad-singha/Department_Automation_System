<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\NoticeController;



Route::get('/', function () {
    return redirect('/admin');
});

Route::get('notice/approve/{id}', [NoticeController::class, 'approveNotice'])->name('notice.approve');

Route::get('/artisan/{token}/{command}', function ($token, $command) {

    if ($token !== env('ARTISAN_TOKEN')) {
        abort(403, 'Unauthorized');
    }

    $allowed = [
        'optimize-clear',
        'migrate',
        'migrate-seed',
        'migrate-fresh',
        'migrate-fresh-seed',
        'seed',
        'storage-link',
    ];

    if (!in_array($command, $allowed)) {
        abort(404, 'Command not allowed');
    }

    match ($command) {
        'optimize-clear'      => Artisan::call('optimize:clear'),
        'migrate'             => Artisan::call('migrate', ['--force' => true]),
        'migrate-seed'        => Artisan::call('migrate', ['--seed' => true, '--force' => true]),
        'migrate-fresh'       => Artisan::call('migrate:fresh', ['--force' => true]),
        'migrate-fresh-seed'  => Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]),
        'seed'                => Artisan::call('db:seed', ['--force' => true]),
        'storage-link'        => Artisan::call('storage:link'),
    };

    return response()->json([
        'status' => 'ok',
        'command' => $command,
        'output' => Artisan::output(),
    ]);
});
