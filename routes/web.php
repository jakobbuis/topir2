<?php

// OAuth2 Todoist
Route::get('login/todoist', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('login/todoist/callback', 'Auth\LoginController@handleProviderCallback');

Route::middleware('auth')->group(function () {
    Route::get('/', 'DashboardController');
});
