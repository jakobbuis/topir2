<?php

use Illuminate\Support\Facades\Route;

Route::post('/webhooks/todoist', 'WebhookController')->name('webhook');
