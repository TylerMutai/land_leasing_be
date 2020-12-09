<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{

   /* protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }*/

    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::guard('api')->guest()) {
            return response()->json(["message" => "Unauthorized"], Response::HTTP_UNAUTHORIZED);
        }

        // other checks
        return $next($request);
    }
}
