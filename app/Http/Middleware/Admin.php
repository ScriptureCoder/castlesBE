<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        if (Auth::user()->role_id == 3) {
            return $next($request);
        }
        $response['status']= 0;
        $response['error']= 'Invalid Access';
        return response()->json($response, 403);
    }
}
