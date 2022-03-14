<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;

class ApiResponse
{
    protected $statusCode = 200;
    protected $headers = [];

    public function __construct()
    {

    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function respond($code, $data = null, $errors = null, $message = null, array $headers = array())
    {
        if ($user = auth()->user()) {
            $userInfo = [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'phone' => $user->phone,
                'address' => $user->address,
                'email' => $user->email,
                'role' => $user->role,
            ];
        }
        $data = [
            'errors' => $errors,
            'data' => $data,
            'user_info' => $userInfo ?? null,
            'message' => $message
        ];

        if (!empty($this->headers)) {
            $headers = array_merge($this->headers, $headers);
        }

        return response()->json($data, $code, $headers);
    }

    public function withData($data = '', $code = 200)
    {
        return $this->respond($code, $data);
    }

    public function errorValidation(Validator $validator) 
    {
        return $this->respond(400, null, $validator->errors());
    }
}
