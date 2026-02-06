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
   
    
    public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $userRole = auth()->user()->role?->name;

    $allowed = collect($roles)->flatMap(fn ($r) => array_map('trim', explode(',', $r)))->all();

    if (! $userRole || ! in_array($userRole, $allowed)) {
        abort(403, 'Unauthorized access.');
    }

    return $next($request);
}

}
