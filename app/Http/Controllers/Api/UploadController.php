<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Services\UploadService;

class UploadController extends ApiController
{
    protected $uploadService;

    public function __construct(Request $request, ApiResponse $response, UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
        parent::__construct($request, $response);
    }

    public function upload(Request $request)
    {
        $url = $this->uploadService->store($request);
        
        if ($url !== false) {
            return response()->json([
                'error' => false,
                'url'   => $url
            ]);
        }
        return $this->response->withData($url);
    }

    public function uploads(Request $request)
    {
        $url = $this->uploadService->storeImage($request);
        
        if ($url !== false) {
            return response()->json([
                $url
            ]);
        }
        return $this->response->withData($url);
    }
}
