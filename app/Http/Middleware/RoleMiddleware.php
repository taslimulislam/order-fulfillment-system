<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐19

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if ($request->user()->role !== $role) {
            abort(403);
        }

        return $next($request);
    }

}
