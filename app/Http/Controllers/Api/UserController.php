<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Services\UserService;
use App\Http\Services\UploadService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    protected $userService, $uploadService;
    public function __construct(Request $request, ApiResponse $response, UserService $userService, UploadService $uploadService)
    {
        $this->userService = $userService;
        $this->uploadService = $uploadService;
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

    //edit user
    public function edit(Request $request)
    {
        $validator = $this->userService->registerValidation($request->all());
        
        if ($validator->fails()) {
            return $this->response->errorValidation($validator);
        }

        $dataInput = $request->except('re_pass');

        $message = $this->userService->edit($dataInput);

        return $this->response->withData($message);
    }

    //contactUs
    public function contactUs(Request $request)
    {
        $validator = $this->userService->contactValidation($request->all());

        if ($validator->fails()) {
            return $this->response->errorValidation($validator);
        }

        $data = $this->userService->sendEmail($request->all());

        return $this->response->withData($data);
    }

}
