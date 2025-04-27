<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Allow access only to users whose user_type matches one of the given roles.
     *
     * Usage in routes/api.php:
     *   Route::get('foo', FooController::class)
     *        ->middleware(['auth:sanctum', 'role:doctor,pharmacist']);
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @param  mixed                     ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->user_type, $roles, true)) {
            return response()->json([
                'message' => 'Forbidden: insufficient role.',
            ], 403);
        }

        return $next($request);
    }
}
