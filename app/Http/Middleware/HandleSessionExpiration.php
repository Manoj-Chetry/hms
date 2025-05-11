<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Session\TokenMismatchException;

class HandleSessionExpiration
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            // Session expired, clear and regenerate session
            Session::flush();
            Session::regenerate();

            // Redirect to the welcome page with a message
            return Redirect::route('welcome')->with('message', 'Your session expired. You have been redirected.');
        }
    }
}
