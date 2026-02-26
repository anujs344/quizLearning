<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Log auth details for debugging why role checks may fail
        try {
            $isAuth = Auth::check();
            $uid = Auth::id();
            $urole = Auth::user() ? Auth::user()->role : null;
        } catch (\Throwable $e) {
            $isAuth = false;
            $uid = null;
            $urole = null;
        }

        Log::info('RoleMiddleware check', [
            'required_role' => $role,
            'is_authenticated' => $isAuth,
            'user_id' => $uid,
            'user_role' => $urole,
            'path' => $request->path(),
            'ip' => $request->ip(),
        ]);

        if (! $isAuth || $urole !== $role) {
            Log::warning('RoleMiddleware aborting request', [
                'required_role' => $role,
                'is_authenticated' => $isAuth,
                'user_id' => $uid,
                'user_role' => $urole,
                'path' => $request->path(),
            ]);

            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}


