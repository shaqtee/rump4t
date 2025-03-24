<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if(Auth::user()->is_admin == 1) {
                    return redirect(RouteServiceProvider::ADMINHOME);
                } else if(Auth::user()->is_admin == 2) {
                    return redirect(RouteServiceProvider::MANAGEHOME);
                } else if(Auth::user()->is_admin == 3) {
                    return redirect(RouteServiceProvider::MANAGEEVENTHOME);
                } else {
                    Auth::logout();
                    return back()->withErrors([
                        'loginError' => 'You are not authorized to Admin or Manage People.',
                    ])->withInput();
                }
            }
        }

        return $next($request);
    }
}
