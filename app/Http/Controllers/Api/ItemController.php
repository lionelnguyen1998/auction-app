<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Services\ItemService;
use App\Models\Auction;
use App\Models\User;
use App\Models\Item;
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
        $validator = $this->itemService->itemValidation($request->all());

        if ($validator->fails()) {
            $brand = $validator->errors()->first("brand_id");
            $name = $validator->errors()->first("name");
            $series = $validator->errors()->first("series");
            $description = $validator->errors()->first("description");
            $startingPrice = $validator->errors()->first("starting_price");
            return [
                "code" => 1001,
                "message" => "brand: " . $brand . "&name: " . $name .
                    "&series: " . $series . "&description: " . $description .
                    "&starting_price: " . $startingPrice,
                "data" => null,
            ];
        }

        $images = array();
        if ($request['images']) {
            foreach ($request['images'] as $key => $value) {
                // $url = $this->uploadService->store($value);
                // array_push($images, $url);
                array_push($images, $value);
            }
            $item = $request->except('images');
        } else {
            $item = $request->all();
        }

        if (sizeof($images) > 4) {
            return [
                "code" => 1007,
                "message" => "Chỉ được thêm tối đa 4 ảnh",
                "data" => null,
            ];
        } else {
            $data = $this->itemService->create($item, $auctionId, $images);
    
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => $data,
            ];
        }
    }

    public function edit(Request $request, $itemId)
    {
        $auctionId = Item::findOrFail($itemId)->auction_id;
        $status = Auction::findOrFail($auctionId)->status;

        if ($status == 4) {
            $validator = $this->itemService->itemValidation($request->all());
    
            if ($validator->fails()) {
                $brand = $validator->errors()->first("brand_id");
                $name = $validator->errors()->first("name");
                $series = $validator->errors()->first("series");
                $description = $validator->errors()->first("description");
                $startingPrice = $validator->errors()->first("starting_price");
                return [
                    "code" => 1001,
                    "message" => "brand: " . $brand . "&name: " . $name .
                        "&series: " . $series . "&description: " . $description .
                        "&starting_price: " . $startingPrice,
                    "data" => null,
                ];
            }

            $images = array();
            if ($request['images']) {
                foreach ($request['images'] as $key => $value) {
                    $url = $this->uploadService->store($value);
                    array_push($images, $url);
                }
                $item = $request->except('images');
            } else {
                $item = $request->all();
            }
            
            $data = $this->itemService->edit($item, $itemId, $images);

            return [
                "code" => 1000,
                "message" => "OK",
                "data" => $data,
            ];
        } else {
            return [
                "code" => 1005,
                "message" => "Không thể chỉnh sửa",
                "data" => null,
            ];
        } 
    }
}
