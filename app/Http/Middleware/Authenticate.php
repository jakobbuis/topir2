<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if (Session::has('current_user')) {
            return $next($request);
        }

        return redirect(route('login'));

    }
}
