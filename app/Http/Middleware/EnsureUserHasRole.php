<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        foreach ($roles as $roleGroup) {
            // Split by comma in case it's passed as 'role:kitchen,owner'
            $individualRoles = explode(',', $roleGroup);
            
            foreach ($individualRoles as $role) {
                $trimmedRole = trim($role);
                if ($request->user()->hasRole($trimmedRole)) {
                    return $next($request);
                }
            }
        }

        abort(403, 'Unauthorized.');
    }
}
