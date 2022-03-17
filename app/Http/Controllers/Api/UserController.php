<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Services\UserService;
use App\Models\User;

class UserController extends ApiController
{
    protected $userService;
    public function __construct(Request $request, ApiResponse $response, UserService $userService)
    {
        $this->userService = $userService;
        parent::__construct($request, $response);
    }

    public function register(Request $request)
    {
        $validator = $this->userService->registerValidation($request->all());

        if ($validator->fails()) {
            return $this->response->errorValidation($validator);
        }

        $data = $this->userService->register($request->all());
        return $this->response->withData($data);
    }

    public function login(Request $request)
    {
        $validator = $this->userService->loginValidation($request->all());

        if ($validator->fails()) {
            return $this->response->errorValidation($validator);
        }

        $data = $this->userService->login($request->all());
        return $this->response->withData($data);
    }
}
