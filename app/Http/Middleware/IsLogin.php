<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('auth');
        }

        if (! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            session()->flash('deActiveUser', 'حساب کاربری شما غیر فعال شده است.');

            return redirect()->route('auth');
        }

        return $next($request);
    }
}
