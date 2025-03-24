<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class userAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $is_admin): Response
    {
        if(Auth::user()->is_admin == $is_admin) {
            return $next($request);
        }
        Auth::logout();
        return back()->withErrors([
            'loginError' => 'You are not authorized to Admin or Manage People.',
        ])->withInput();
    }
}
