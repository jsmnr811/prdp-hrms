<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        logger('CheckRole middleware: user id '.$user->id.', roles: '.implode(',', $roles).', user roles: '.$user->roles->pluck('name')->join(','));

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                logger('CheckRole: user has role '.$role);

                return $next($request);
            }
        }

        logger('CheckRole: user does not have required role, aborting');
        return redirect('/dashboard');
    }
}
