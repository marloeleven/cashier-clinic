<?php
namespace App\Http\Middleware;

use Closure;

class Supervisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array($request->user()->type, ['SUPERVISOR', 'ADMIN', 'SUPER_ADMIN'])) {
          return $next($request);
        }

        return response()->json([
            "success" => 0,
            "message" => 'Unauthorized.',
            "errors"  => [
                "Unauthorized."
            ],
            "code"    => 401
        ]);
    }
}