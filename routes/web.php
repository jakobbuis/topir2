<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// OAuth2 Todoist
Route::get('login/todoist', [Auth\LoginController::class, 'redirectToProvider'])->name('login');
Route::get('login/todoist/callback', [Auth\LoginController::class, 'handleProviderCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class);
});
