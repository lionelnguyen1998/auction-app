<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\UserService;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Controllers\Api\ApiController;

class AuthController extends ApiController
{
    protected $userService;

    public function __construct(UserService $userService, ApiResponse $response, Request $request)
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
        $this->userService = $userService;
        parent::__construct($request, $response);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        
        $validator = $this->userService->loginValidation($credentials);

        if ($validator->fails()) {
            $emailM = $validator->errors()->first("email");
            $passwordM = $validator->errors()->first("password");
            return [
                "code" => 1001,
                "message" => "email: " . $emailM . " &password: " . $passwordM,
                "data" => null,
            ];
        }

        if (! $token = auth()->attempt($credentials)) {
            return [
                "code" => 1002,
                "message" => "メールとパスワードは違いました",
                "data" => null,
            ];
        }
        
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => null,
        ];
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'user' => [
                'name' => $this->guard()->name,
                'role' => $this->guard()->role,
                'avatar' => $this->guard()->avatar,
                'email' => $this->guard()->email,
                'address' => $this->guard()->address,
                'phone' => $this->guard()->phone,
                'user_id' => $this->guard()->user_id,
            ],
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 6000000
        ];
        
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function guard()
    {
        return Auth::Guard('api')->user();
    }
}