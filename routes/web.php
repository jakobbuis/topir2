<?php

// OAuth2 Todoist
Route::get('login/todoist', 'Auth\LoginController@redirectToProvider');
Route::get('login/todoist/callback', 'Auth\LoginController@handleProviderCallback');

Route::middleware('auth.basic')->group(function () {
    Route::get('/', 'DashboardController');
});
