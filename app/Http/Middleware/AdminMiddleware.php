<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and if the user is an admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // If not, redirect to a page (e.g., home) with an error message
        return redirect('/')->with('error', 'You do not have admin access');
    }
}
