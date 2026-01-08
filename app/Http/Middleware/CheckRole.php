<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get user's role name
        $userRole = auth()->user()->role->name ?? null;

        // Check if user has the required role
        if ($userRole !== $role) {
            abort(403, 'Unauthorized access. This page is only accessible to ' . $role . ' users.');
        }

        return $next($request);
    }
}
