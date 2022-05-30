<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Services\ItemService;
use App\Models\Auction;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\Bid;
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
        if (!Auction::find($auctionId)) {
            return [
                "code" => 9996,
                "message" => "Id truyền vào không tồn tại",
                "data" => null,
            ];
        }

        $checkAuction = Auction::where('auction_id', $auctionId)
            ->where('status', 4)
            ->get()
            ->first();

        $countItem = Item::where('auction_id', $auctionId)
            ->count('item_id');

        if ((! $checkAuction) || ($countItem > 0)) {
            return [
                "code" => 9995,
                "message" => "Không thể thêm item mới với phiên đấu giá này",
                "data" => null,
            ];
        }
    
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
        $itemInfo = Item::findOrFail($itemId);
        $auctionId = $itemInfo->auction_id;
        $status = Auction::findOrFail($auctionId)->status;
        $imageItem = Image::where('item_id', $itemId)
            ->get()
            ->pluck('image', 'image_id')
            ->toArray();

        if ($status == 4) {
            $validator = $this->itemService->itemValidationEdit($request->all(), $itemId);
    
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

    public function listCategoryOfItem() {
        $userId = auth()->user()->user_id;
        $categoryId = Item::where('buying_user_id', $userId)
            ->get()
            ->pluck('category_id');

        $categories = Category::whereIn('category_id', $categoryId)
            ->select('name', 'image', 'category_id')
            ->get();

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => [
                'category' => $categories->map(function ($category) {
                    return [
                        'category_id' => $category->category_id,
                        'name' => $category->name,
                        'image' => $category->image
                    ];
                }),
                'all' => $categories
            ]
        ];
    }
    //list item user dau gia thanh cong
    public function listBuy($categoryId) {
        $userId = auth()->user()->user_id;
        $auctionId = Item::where('buying_user_id', $userId)
            ->where('category_id', $categoryId)
            ->get()
            ->pluck('auction_id');

        $auctions = Auction::with('items')
            ->whereIn('auction_id', $auctionId)
            ->get();

        $itemId = Item::where('auction_id', $auctionId)
            ->get()
            ->pluck('item_id');

        $images = $this->itemService->getImageLists($itemId);

        $total = Item::where('category_id', $categoryId)
            ->where('buying_user_id', $userId)
            ->count('category_id');

        $data = [
            'info' => $auctions->map(function ($auction) use ($images) {
                return [
                    'item' => [
                        'item_id' => $auction['items']->item_id,
                        'name' => $auction['items']->name,
                        'mainImage' => $images[0],
                    ]
                ];
            }),
            'total' => $total
        ];

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];

    }

    public function detail($itemId) {
        $auctionId = Item::findOrFail($itemId)->auction_id;
        $brandId = Item::findOrFail($itemId)->brand_id;
        $categoryId = Item::findOrFail($itemId)->category_id;
        $images = $this->itemService->getImageLists($itemId);
 
        $auction = Auction::with('items', 'userSelling')
            ->where('auction_id', $auctionId)
            ->get()
            ->first();

        $like = Favorite::where('auction_id', $auctionId)
            ->where('is_liked', 1)
            ->where('user_id', auth()->user()->user_id)
            ->get()
            ->pluck('is_liked')
            ->first();

        $brand = Brand::where('brand_id', $brandId)
            ->get()
            ->pluck('name')
            ->first();

        $category = Category::where('category_id', $categoryId)
            ->get()
            ->pluck('name')
            ->first();

        $maxPrice = Bid::where('auction_id', $auctionId)
            ->where('user_id', auth()->user()->user_id)
            ->max('price');

        $data = [
            'auction' => [
                'auction_id' => $auction->auction_id,
                'title' => $auction->title,
                'start_date' => $auction->start_date,
                'end_date' => $auction->end_date,
                'status' => $auction->status
            ],
            'item' => [
                'item_id' => $auction['items']->item_id,
                'name' => $auction['items']->name,
                'series' => $auction['items']->series,
                'description' => $auction['items']->description,
                'starting_price' => $auction['items']->starting_price,
                'selling_info' => $auction['items']->selling_info,
                'images' => $images,
                'brand' => $brand,
                'category' => $category,
                'max_price' => $maxPrice,
                'like' => $like
            ],
            'selling_user' => [
                'selling_user_id' => $auction['userSelling']['user_id'],
                'selling_user_name' => $auction['userSelling']['name'],
                'selling_user_avatar' => $auction['userSelling']['avatar']
            ],
            'buying_user' => [
                'buying_user_id' => auth()->user()->user_id,
                'buying_user_name' => auth()->user()->name,
                'buying_user_phone' =>  auth()->user()->phone,
                'buying_user_address' => auth()->user()->address,
            ]
        ];

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }
    public function info($itemId) {
        $item = Item::findOrFail($itemId);
        $images = $this->itemService->getImageLists($itemId);
        $data = [
            'name' => $item->name,
            'series' => $item->series,
            'description' => $item->description,
            'starting_price' => $item->starting_price,
            'brand_id' => $item->brand_id,
            'images' => $images
        ];

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }
}
