<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Models\Brand;

class BrandController extends ApiController
{
    public function __construct(Request $request, ApiResponse $response)
    {
        parent::__construct($request, $response);
    }

    public function index() {
        $data = Brand::getBrandList();
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }
}
