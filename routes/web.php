<?php

// OAuth2 Todoist
Route::get('login/todoist', 'Auth\LoginController@redirectToProvider');
Route::get('login/todoist/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/', 'DashboardController');
