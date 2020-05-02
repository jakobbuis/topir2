<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Socialite;

class LoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('todoist')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('todoist')->user();
        Session::put('current_user', $user);
        return redirect('/');
    }
}
