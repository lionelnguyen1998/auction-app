<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Services\ItemService;
use App\Models\Auction;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemValue;
use App\Http\Services\UploadService;

class ItemController extends ApiController
{
    protected $itemService, $uploadService;

    public function __construct(Request $request, ItemService $itemService, ApiResponse $response, UploadService $uploadService)
    {
        $this->itemService = $itemService;
        $this->uploadService = $uploadService;
        parent::__construct($request, $response);
    }

    public function create(Request $request, $auctionId)
    {
        $dataRequest = $request->all();
        $validator = $this->itemService->itemValidation($request->all());

        if ($validator->fails()) {
            return $this->response->errorValidation($validator);
        }

        $images = array();
        foreach ($request['images'] as $key => $value) {
            $url = $this->uploadService->store($value);
            array_push($images, $url);
        }

        $item = $request->except('images');
        $data = $this->itemService->create($item, $auctionId, $images);

        return $this->response->withData($data);
    }

    public function edit(Request $request, $itemId)
    {
        $validator = $this->itemService->itemValidation($request->all());

        if ($validator->fails()) {
            return $this->response->errorValidation($validator);
        }

        $data = $this->itemService->edit($request->all(), $itemId);

        return $this->response->withData($data);
    }
}
