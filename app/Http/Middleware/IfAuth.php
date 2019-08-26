<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Route;

class IfAuth
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
        $token = Request::create(
            'api/user/check',
            'GET',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$request->header('Authorization'),
                ],
            ]
        );

        $response = Route::dispatch($token);
        $user = User::find($response->getContent());
        if ($user){
            $request->request->add(['auth'=>$user->toArray()]);
            return $next($request);
        }
        return $next($request);
    }
}
