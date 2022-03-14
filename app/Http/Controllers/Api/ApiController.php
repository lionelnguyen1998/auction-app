<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiResponse;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function __construct(Request $request, ApiResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
