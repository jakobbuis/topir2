<?php

namespace App\Providers;

use App\TodoistSocialiteProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class OAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Add the custom Todoist driver to Socialite
        $socialite = app(Factory::class);
        // @phpstan-ignore-next-line
        $socialite->extend(
            'todoist',
            function ($app) use ($socialite) {
                // @phpstan-ignore-next-line
                return $socialite->buildProvider(TodoistSocialiteProvider::class, config('services.todoist'));
            }
        );
    }
}
