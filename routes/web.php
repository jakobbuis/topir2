<?php

Route::get('login/todoist', 'Auth\LoginController@redirectToProvider');
Route::get('login/todoist/callback', 'Auth\LoginController@handleProviderCallback');
