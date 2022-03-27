<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Models\Slider;

class HomeController extends ApiController
{
    public function __construct(Request $request, ApiResponse $response)
    {
        parent::__construct($request, $response);
    }

    public function slider()
    {
        $slider = Slider::all();
        return $this->response->withData($slider);
    }
}
