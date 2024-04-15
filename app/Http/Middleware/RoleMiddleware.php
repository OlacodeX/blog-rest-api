<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        
        if (!Auth::check()) {
            abort(401);
        }

        $user_type = explode("\\",Auth::user()->profile_type)[2];
        if ($user_type !== $role) {
            abort(403, 'You are not allowed to carry out this action');
        }
        return $next($request);
    }
}
