<?php

namespace App;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TodoistSocialiteProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['data:read'];

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://todoist.com/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://todoist.com/oauth/access_token';
    }

    protected function getUserByToken($token)
    {
        return [];
    }

    protected function mapUserToObject(array $user)
    {
         return (new User())->setRaw($user);
    }
}
