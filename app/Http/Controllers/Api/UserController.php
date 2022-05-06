<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Services\UserService;
use App\Http\Services\UploadService;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Auction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends ApiController
{
    protected $userService, $uploadService;
    public function __construct(Request $request, ApiResponse $response, UserService $userService, UploadService $uploadService)
    {
        $this->userService = $userService;
        $this->uploadService = $uploadService;
        parent::__construct($request, $response);
    }

    public function signup(Request $request)
    {
        $validator = $this->userService->signupValidation($request->all());

        if ($validator->fails()) {
            $name = $validator->errors()->first("name");
            $phone = $validator->errors()->first("phone");
            $address = $validator->errors()->first("address");
            $email = $validator->errors()->first("email");
            $password = $validator->errors()->first("password");
            $rePass = $validator->errors()->first("re_pass");
            $avatar = $validator->errors()->first("avatar");
            return [
                "code" => 1001,
                "message" => "name: " . $name . "&phone: " . $phone . "&address: " . $address .
                    "&email: " . $email . "&password: " . $password . 
                    "&re_pass: " . $rePass . " &avatar: " . $avatar,
                "data" => null,
            ];
        }

        $data = $this->userService->signup($request->all());
        
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    //edit user
    public function edit(Request $request)
    {
        $validator = $this->userService->signupValidation($request->all());
        
        if ($validator->fails()) {
            $name = $validator->errors()->first("name");
            $phone = $validator->errors()->first("phone");
            $address = $validator->errors()->first("address");
            $email = $validator->errors()->first("email");
            $password = $validator->errors()->first("password");
            $rePass = $validator->errors()->first("re_pass");
            $avatar = $validator->errors()->first("avatar");
            return [
                "code" => 1001,
                "message" => "name: " . $name . "&phone: " . $phone . "&address: " . $address .
                    "&email: " . $email . "&password: " . $password . 
                    "&re_pass: " . $rePass . " &avatar: " . $avatar,
                "data" => null,
            ];
        }

        $dataInput = $request->except('re_pass');

        $data = $this->userService->edit($dataInput);

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function info()
    {
        $currentUser = auth()->user();
        $userId = $currentUser->user_id;

        $totalLike = Favorite::where('user_id', $userId)
            ->where('is_liked', 1)
            ->count('user_id');
        $totalAuctions = Auction::where('selling_user_id', $userId)
            ->count('selling_user_id');

        $data = [
            'name' => $currentUser->name,
            'phone' => $currentUser->phone,
            'address' => $currentUser->address,
            'avatar' => $currentUser->avatar,
            'role' => $currentUser->role,
            'email' => $currentUser->email,
            'total_like' => $totalLike,
            'total_auctions' => $totalAuctions
        ];
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    //contactUs
    public function contactUs(Request $request)
    {
        $validator = $this->userService->contactValidation($request->all());

        if ($validator->fails()) {
            $name = $validator->errors()->first("name");
            $phone = $validator->errors()->first("phone");
            $email = $validator->errors()->first("email");
            $content = $validator->errors()->first("content");
            $reportType = $validator->errors()->first("report_type");
            return [
                "code" => 1001,
                "message" => "name: " . $name . "&phone: " . $phone .
                    "&email: " . $email . "&content: " . $content .
                    "&report_type: " . $reportType,
                "data" => null,
            ];
        }

        $data = $this->userService->sendEmail($request->all());

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

}
