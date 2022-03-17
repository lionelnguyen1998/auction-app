<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\Http\Controllers\Api\ApiResponse;

class AuthenticateApi
{
    public function __construct(ApiResponse $response)
    {
        $this->response = $response;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-API-KEY');

        if (empty($token) || $toke == null) {
            return $this->response->errorUnauthorized();
        }

        $userDecode = JWT::decode($token, config('const.jwt_key'), array('HS256'));
        $user = User::findBy($userDecode->user_id);

        if (empty($user)) {
            return $this->response->errorUnauthorized();
        }

        auth()->login($user);

        return $next($request);
    }
}
