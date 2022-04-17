<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Services\CategoryService;
use App\Models\Auction;
use App\Models\Category;

class CategoryController extends ApiController
{
    protected $categoryService;

    public function __construct(Request $request, CategoryService $categoryService, ApiResponse $response)
    {
        $this->categoryService = $categoryService;
        parent::__construct($request, $response);
    }

    public function index() {
        $data = $this->categoryService->getCategoryList();
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }
}
