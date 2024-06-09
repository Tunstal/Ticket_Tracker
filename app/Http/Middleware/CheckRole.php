<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

     // The handle method checks if the user is authenticated and has the correct role. 
     // If the user is not authenticated, they are redirected to the login page. If the user 
     // is authenticated but does not have the correct role, they are redirected to the home page.
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return Inertia::location('/login');
        }

        $user = Auth::user();
        if ($user->role !== $role) {
            return Inertia::location('/');
        }


        return $next($request);
    }
}
