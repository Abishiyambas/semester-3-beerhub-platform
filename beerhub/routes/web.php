<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresetController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/batch-logs', [BatchController::class, 'batchLogs'])->name('batch-logs');

        Route::get('/presets', [PresetController::class, 'list'])->name('presets');
        Route::post('/presets', [PresetController::class, 'store'])->name('presets.store');
        Route::put('/presets/{preset}', [PresetController::class, 'update'])->name('presets.update');
        Route::post('/presets/start', [PresetController::class, 'start'])->name('presets.start');

        Route::delete('/queue', [QueueController::class, 'destroySelected'])->name('queue.destroySelected');

        Route::post('/command/{id}', [CommandController::class, 'send'])->whereNumber('id')->name('command.send');
    });
