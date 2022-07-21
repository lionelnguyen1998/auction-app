<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Http\Services\AuctionService;
use App\Http\Services\CategoryService;
use App\Http\Services\ItemService;
use App\Models\Auction;
use App\Models\Slider;
use App\Models\User;
use App\Models\Bid;
use App\Models\Item;
use App\Models\Brand;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Rate;
use App\Models\Category;
use App\Models\Image;

class AuctionController extends ApiController
{
    protected $auctionService, $categoryService, $itemService;

    public function __construct(Request $request, AuctionService $auctionService, CategoryService $categoryService, ItemService $itemService, ApiResponse $response)
    {
        $this->auctionService = $auctionService;
        $this->categoryService = $categoryService;
        $this->itemService = $itemService;
        parent::__construct($request, $response);
    }

    //detail auctions
    public function detail($auctionId)
    {
        $itemId = Item::where('auction_id', $auctionId)
            ->get()
            ->pluck('item_id')
            ->first();

        $auction = $this->auctionService->getDetailAuctions($auctionId);
        
        $maxPrice = $this->auctionService->getMaxPrice($auctionId);
        $bids = $this->auctionService->getBids($auctionId);
        $comments = $this->auctionService->getComments($auctionId);
        if ($itemId) {
            $images = $this->itemService->getImageLists($itemId);
        }
        $brand = Brand::where('brand_id', $auction[0]['items']['brand_id'])
            ->get()
            ->pluck('name')
            ->first();
        $status = config('const.status');
        $index = $auction[0]['status'];
        if (auth()->user()) {
            $liked = Favorite::where('auction_id', $auctionId)
                ->where('user_id', auth()->user()->user_id)
                ->get()
                ->pluck('is_liked')
                ->first();
        }

        if ($index === 6 || $index === 7 || $index === 8) {
            $buyingUserId = Item::where('item_id', $itemId)
                ->get()
                ->pluck('buying_user_id')
                ->first();
            $buyingUser = User::findOrFail($buyingUserId);
        }

        $data = [
            'auctions' => [
                'auction_id' => $auction[0]['auction_id'],
                'title' => $auction[0]['title'],
                'start_date' => $auction[0]['start_date'],
                'end_date' => $auction[0]['end_date'],
                'statusId' => $index,
                'status' => $status[$index],
            ],
            'category' => [
                'name' => $auction[0]['category']['name'],
                'image' => $auction[0]['category']['image'],
                'type' => $auction[0]['category']['type'],
            ],
            'items' => [
                'item_id' => $auction[0]['items']['item_id'],
                'name' => $auction[0]['items']['name'],
                'buying_user_id' => $auction[0]['items']['buying_user_id'],
                'brand' => $brand,
                'series' => $auction[0]['items']['series'],
                'description' => $auction[0]['items']['description'],
                'starting_price' => $auction[0]['items']['starting_price'],
                'selling_info' => $auction[0]['items']['selling_info'],
                'mainImage' => $images[0] ?? null,
                'images' => $images ?? null,
            ],
            'selling_user' => [
                'selling_user_id' => $auction[0]['user_selling']['user_id'],
                'selling_user_name' => $auction[0]['user_selling']['name'],
                'selling_user_avatar' => $auction[0]['user_selling']['avatar']
            ],
            'buying_user' => [
                'buying_user_id' => $buyingUser->user_id ?? null,
                'buying_user_name' => $buyingUser->name ?? null,
                'buying_user_phone' =>  $buyingUser->phone ?? null,
                'buying_user_address' => $buyingUser->address ?? null,
            ],
            'max_bid' => $maxPrice,
            'like' => auth()->user() ? $liked : null
        ];

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function detail1($auctionId)
    {
        $auction = $this->auctionService->getDetailAuctions1($auctionId);
        $status = config('const.status');
        $index = $auction[0]['status'];
        $data = [
            'auctions' => [
                'auction_id' => $auction[0]['auction_id'],
                'title' => $auction[0]['title'],
                'start_date' => $auction[0]['start_date'],
                'end_date' => $auction[0]['end_date'],
                'statusId' => $index,
                'status' => $status[$index],
            ],
            'category' => [
                'name' => $auction[0]['category']['name'],
                'image' => $auction[0]['category']['image'],
                'type' => $auction[0]['category']['type'],
            ],
        ];

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function listAuctionByType($typeId, $statusId, Request $request)
    {
        $auctions = $this->auctionService->getListAuctionByType($typeId, $statusId, $request->all());

        $categoryId = Category::where('type', $typeId)
            ->get()
            ->pluck('category_id');

        if ($statusId == 0) {
            $total = Auction::whereIn('category_id', $categoryId)
                ->whereIn('status', [1, 2, 3])
                ->count('auction_id');
        } else {
            $total = Auction::whereIn('category_id', $categoryId)
                ->where('status', $statusId)
                ->count('auction_id');
        }

        $status = config('const.status');
        $type = config('const.categories');
        $data = [
            'auctions' => $auctions->map(function ($auction) use ($status) {
                $index = $auction->status;
                return [
                    'auction_id' => $auction->auction_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'statusId' => $index,
                    'status' => $status[$index],
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image']
                    ],
                ];
            }),
            'type' => $type[$typeId],
            'total' => $total
        ];
        
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function listAuctionsByUser($statusId, Request $request)
    {
        $userId = auth()->user()->user_id;
        $auctions = $this->auctionService->getListAuctionsByUser($userId, $statusId, $request->all());
        if ($statusId == 0) {
            $total = Auction::where('selling_user_id', $userId)
                ->count('auction_id');
        } else {
            $total = Auction::where('selling_user_id', $userId)
                ->where('status', $statusId)
                ->count('auction_id');
        }

        $status = config('const.status');
        $data = [
            'auctions' => $auctions->map(function ($auction) use ($status) {
                $index = $auction->status;
                return [
                    'auction_id' => $auction->auction_id,
                    'selling_user_id' => $auction->selling_user_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'statusId' => $index,
                    'status' => $status[$index],
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image'],
                        'type' => $auction['category']['type'],
                    ],
                ];
            }),
            'total' => $total
        ];
        
        return [
            'code' => 1000,
            'message' => 'OK',
            'data' => $data
        ];
    }

    public function listAuctionsByUserK($userId, $statusId, Request $request)
    {
        $auctions = $this->auctionService->getListAuctionsByUserK($userId, $statusId, $request->all());
        if ($statusId == 0) {
            $total = Auction::where('selling_user_id', $userId)
                ->where('status', '<>', 4)
                ->count('auction_id');
        } else {
            $total = Auction::where('selling_user_id', $userId)
                ->where('status', $statusId)
                ->count('auction_id');
        }

        $userInfo = User::where('user_id', $userId)
            ->get()
            ->first();

        $status = config('const.status');
        $data = [
            'auctions' => $auctions->map(function ($auction) use ($status) {
                $index = $auction->status;
                return [
                    'auction_id' => $auction->auction_id,
                    'selling_user_id' => $auction->selling_user_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'statusId' => $index,
                    'status' => $status[$index],
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image'],
                        'type' => $auction['category']['type'],
                    ],
                ];
            }),
            'userInfo' => [
                'name' => $userInfo->name,
                'avatar' => $userInfo->avatar,
                'phone' => $userInfo->phone,
                'email' => $userInfo->email,
                'role' => $userInfo->role,
                'address' => $userInfo->address ?? '--',
            ],
            'total' => $total
        ];
        
        return [
            'code' => 1000,
            'message' => 'OK',
            'data' => $data
        ];
    }

    public function listAuctionOfCategory($categoryId, $statusId, Request $request)
    {
        $categoryInfo = Category::where('category_id', $categoryId)
            ->select('name','image', 'type')
            ->get()
            ->first();

        $auctions = $this->auctionService->getListAuctionOfCategory($request->all(), $categoryId, $statusId);
        if ($statusId == 0) {
            $total = Auction::where('category_id', $categoryId)
                ->whereIn('status', [1, 2, 3])
                ->count('auction_id');
        } else {
            $total = Auction::where('status', $statusId)
                ->where('category_id', $categoryId)
                ->count('auction_id');
        }

        $status = config('const.status');
        $data = [
            'auctions' => $auctions->map(function ($auction) use ($status) {
                $index = $auction->status;
                return [
                    'auction_id' => $auction->auction_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'statusId' => $index,
                    'status' => $status[$index],
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image'],
                        'type' => $auction['category']['type'],
                    ],
                ];
            }),
            'category' => [
                'name' => $categoryInfo['name']
            ],
            'total' => $total
        ];
        
        return [
            'code' => 1000,
            'message' => 'OK',
            'data' => $data
        ];
    }

    //create auctions
    public function create(Request $request)
    {
        $validator = $this->auctionService->auctionValidation($request->all());

        if ($validator->fails()) {
            $category = $validator->errors()->first("category_id");
            $startDate = $validator->errors()->first("start_date");
            $endDate = $validator->errors()->first("end_date");
            $title = $validator->errors()->first("title_ni");
            return [
                "code" => 1001,
                "message" => "category: " . $category . "&start_date: " . $startDate .
                    "&end_date: " . $endDate . "&title: " . $title,
                "data" => null,
            ];
        }

        $data = $this->auctionService->create($request->all());
        
        return [
            'code' => 1000,
            'message' => 'OK',
            'data' => $data
        ];
    }

    public function uploadStatus()
    {
        $auctions = Auction::where('status', '<>', 3)
            ->get();
        foreach ($auctions as $key => $value) {
            $auction = Auction::findOrFail($value->auction_id);
            if ($auction && ($value->status != 4) && ($value->status != 6) && ($value->status != 7) && ($value->status != 8)) {
                if ($value->start_date <= now() && $value->end_date > now()) {
                    $auction->status = 1;
                    $auction->update();
                } elseif ($value->end_date <= now()) {
                    $auction->status = 3;
                    $auction->update();
                } else {
                    $auction->status = 2;
                    $auction->update();
                }
            }
                
            if (($value->status == 4) && ($value->end_date < now())) {
                $auction->status = 5;
                $auction->reason = 'Đã quá thời gian duyệt';
                $auction->update();

                $itemId = Item::where('auction_id', '=', $value->auction_id)
                    ->get()
                    ->pluck('item_id')
                    ->toArray();

                if (isset($itemId[0])) {
                    Image::where('item_id', '=', $itemId[0])->delete();
                    Item::where('item_id', '=', $itemId[0])->delete();
                }
                Auction::findOrFail($value->auction_id)->delete();
            }
        }
        
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => null,
        ];
    }

    //delete auctions
    public function delete($auctionId)
    {
        $status = Auction::findOrFail($auctionId)->status;

        if ($status == 3) {
            $itemId = Item::where('auction_id', '=', $auctionId)
                ->get()
                ->pluck('item_id')
                ->toArray();
    
            if (isset($itemId[0])) {
                Item::where('item_id', '=', $itemId[0])->delete();
                Image::where('item_id', '=', $itemId[0])->delete();
                Bid::where('auction_id', '=', $auctionId)->delete();
                Comment::where('auction_id', '=', $auctionId)->delete();
                Favorite::where('auction_id', '=', $auctionId)->delete();
                Auction::find($auctionId)->delete();
                $message = "Da xoa";
            }
        } else {
            $message = "Khong the xoa";
        }
        return $this->response->withData($message);
    }

    public function deleteAuction($auctionId)
    {
        $auction = Auction::findOrFail($auctionId);
        $status = $auction->status;
        $sellingUserId = $auction->selling_user_id;

        if ($sellingUserId != auth()->user()->user_id) {
            return [
                "code" => 1006,
                "message" => "Không có quyền",
                "data" => null,
            ];
        } else {
            if ($status == 4) {
                $itemId = Item::where('auction_id', '=', $auctionId)
                    ->get()
                    ->pluck('item_id')
                    ->toArray();
        
                if (isset($itemId[0])) {
                    Item::where('item_id', '=', $itemId[0])->forceDelete();
                    Image::where('item_id', '=', $itemId[0])->forceDelete();
                }
                Auction::find($auctionId)->forceDelete();
    
                return [
                    "code" => 1000,
                    "message" => "OK",
                    "data" => null,
                ];
            } else {
                return [
                    "code" => 9994,
                    "message" => "Không thể xóa",
                    "data" => null,
                ];
            }
        }
    }

    //edit auction when auctions đang chờ duyệt
    public function edit(Request $request, $auctionId)
    {
        $status = Auction::findOrFail($auctionId)->status;
        $userSellingId = Auction::findOrFail($auctionId)->selling_user_id;

        if ((auth()->user()->user_id == $userSellingId) && $status == 4) {
            $validator = $this->auctionService->auctionValidationEdit($request->all(), $auctionId);
    
            if ($validator->fails()) {
                $category = $validator->errors()->first("category_id");
                $startDate = $validator->errors()->first("start_date");
                $endDate = $validator->errors()->first("end_date");
                $title = $validator->errors()->first("title_ni");
                return [
                    "code" => 1001,
                    "message" => "category: " . $category . "&start_date: " . $startDate .
                        "&end_date: " . $endDate . "&title: " . $title,
                    "data" => null,
                ];
            }

            $data = $this->auctionService->edit($auctionId, $request->all());

            return [
                "code" => 1000,
                "message" => "OK",
                "data" => $data,
            ];
        } else if ($status != 4) {
            return [
                "code" => 1005,
                "message" => "Không thể chỉnh sửa",
                "data" => null,
            ];
        } else {
            return [
                "code" => 1006,
                "message" => "Không có quyền chỉnh sửa",
                "data" => null,
            ];
        }
    }

    //create comment
    public function comments($auctionId, Request $request) 
    {
        $validator = $this->auctionService->commentValidation($request->all());
        if ($validator->fails()) {
            $content = $validator->errors()->first("content");
            return [
                "code" => 1001,
                "message" => $content,
                "data" => null,
            ];
        }

        $status = Auction::findOrFail($auctionId)->status;

        if ($status != 4) {
            $data = $this->auctionService->comments($auctionId, $status, $request->all());
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => $data,
            ];
        } else {
            return [
                "code" => 1008,
                "message" => "Không thể bình luận",
                "data" => null,
            ];
        }
    }

    //list comment
    public function listComments($auctionId, Request $request)
    {
        $page = $request->index;
        $perPage = $request->count;

        $auction = Auction::findOrFail($auctionId);

        $comments = Comment::with('users')
            ->where('auction_id', $auctionId)
            ->orderBy('created_at', 'DESC')
            ->forPage($page, $perPage)
            ->get();

        $total = Comment::where('auction_id', $auctionId)
            ->count('comment_id');

        $data = [
            'comments' => $comments->map(function($comment) {
                return [
                    'comment_id' => $comment->comment_id,
                    'user_name' => $comment['users']['name'],
                    'user_avatar' => $comment['users']['avatar'],
                    'content' => $comment->content,
                    'updated_at' => $comment->updated_at->format('Y/m/d H:i:s'),
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

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $auctionId = Comment::findOrFail($commentId)->auction_id;
        $userId = auth()->user()->user_id;
        $userCommentId = Comment::where('comment_id', $commentId)
            ->get()
            ->pluck('user_id')
            ->first();
        
        if ($userId != $userCommentId) {
            return [
                "code" => 1006,
                "message" => "削除できません。",
                "data" => null,
            ];
        } else {
            $comment->forceDelete();
            $total = Comment::where('auction_id', $auctionId)
                ->count('comment_id');
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => [
                    'total' => $total,
                    'message' => '削除しました。'
                ],
            ];
        }

    }

    //create bids
    public function bids($auctionId, Request $request)
    {
        $checkAuctionExist = Auction::find($auctionId);
        if (!$checkAuctionExist) {
            return [
                "code" => 9993,
                "message" => "ID không hợp lệ",
                "data" => null,
            ];
        }
        
        $status = Auction::find($auctionId)->status;

        $startPrice = Item::where('auction_id', $auctionId)
            ->get()
            ->pluck('starting_price')
            ->first();

        $validator = $this->auctionService->bidValidation($request->all(), $auctionId, $startPrice);

        if ($validator->fails()) {
            $price = $validator->errors()->first("price");
            return [
                "code" => 1001,
                "message" => "price: " . $price,
                "data" => null,
            ];
        }
        
        if ($status == 1) {
            $data = $this->auctionService->bids($auctionId, $status, $request->all());
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => $data,
            ];
        } else {
            return [
                "code" => 1008,
                "message" => "Không thể trả giá",
                "data" => null,
            ];
        }
    }

    //list bids
    public function listBids($auctionId, Request $request)
    {
        $page = $request->index;
        $perPage = $request->count;

        $auction = Auction::findOrFail($auctionId);
        $total = Bid::where('auction_id', $auctionId)
            ->count('bid_id');

        $bids = Bid::with('users')
            ->where('auction_id', $auctionId)
            ->orderBy('price', 'DESC')
            ->orderBy('updated_at', 'DESC')
            ->forPage($page, $perPage)
            ->get();

        $data = [
            'bids' => $bids->map(function($bid) {
                return [
                    'user_name' => $bid['users']['name'],
                    'user_avatar' => $bid['users']['avatar'],
                    'price' => $bid->price,
                    'updated_at' => $bid->updated_at->format('Y/m/d H:i:s'),
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

    public function listRates($auctionId, Request $request) {
        $page = $request->index;
        $perPage = $request->count;

        $auction = Auction::findOrFail($auctionId);
        $total = Rate::where('auction_id', $auctionId)
            ->count('auction_id');

        $rates = Rate::with('users')
            ->where('auction_id', $auctionId)
            ->orderBy('created_at', 'DESC')
            ->forPage($page, $perPage)
            ->get();

        $data = [
            'rates' => $rates->map(function($rate) {
                return [
                    'user_name' => $rate['users']['name'],
                    'user_avatar' => $rate['users']['avatar'],
                    'content' => $rate->content,
                    'star' => $rate->star,
                    'image' => $rate->image,
                    'updated_at' => $rate->updated_at->format('Y/m/d H:i:s'),
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

    public function maxBid($auctionId)
    {
        $maxPrice = $this->auctionService->getMaxPrice($auctionId);

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $maxPrice,
        ];
    }

    //update like
    public function updateLike($auctionId)
    {
        $userId = auth()->user()->user_id;

        $checkLiked = Favorite::where('auction_id', $auctionId)
            ->where('user_id', $userId)
            ->get()
            ->toArray();

        if ($checkLiked) {
            $is_liked = Favorite::where('auction_id', $auctionId)
                ->where('user_id', $userId)
                ->get()
                ->first();

            $is_liked->timestamps = false;
            $is_liked->is_liked = !($is_liked->is_liked);
            $is_liked->update();
            $data = [
                'user_id' => $is_liked->user_id,
                'auction_id' => $is_liked->auction_id,
                'is_liked' => $is_liked->is_liked
            ];
        } else {
            $is_liked1 = Favorite::insert([
                'user_id' => auth()->user()->user_id,
                'auction_id' => $auctionId ?? null,
                'is_liked' => true
            ]);

            $data = [
                'user_id' => auth()->user()->user_id,
                'auction_id' => $auctionId,
                'is_liked' => true,
            ];
        }

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function listLikes($statusId, Request $request) {

        $userId = auth()->user()->user_id;
        $auctions = $this->auctionService->getListAuctionLike($statusId, $request->all());
        if ($statusId == 0) {
            $total = Favorite::where('user_id', $userId)
                ->where('is_liked', 1)
                ->count('auction_id');
        } else {
            $total = Favorite::join('auctions', 'auctions.auction_id', '=', 'favories.auction_id')
                ->where('auctions.status', $statusId)
                ->where('favories.user_id', $userId)
                ->where('favories.is_liked', 1)
                ->count('favories.auction_id');
        }
        $status = config('const.status');
        $data = [
            'auctions' => $auctions->map(function ($auction) use ($status) {
                $index = $auction->status;
                return [
                    'auction_id' => $auction->auction_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'statusId' => $index,
                    'status' => $status[$index],
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image'],
                        'type' => $auction['category']['type'],
                    ],
                    'is_liked' => true,
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

    public function totalLikes($auctionId) 
    {
        Auction::findOrFail($auctionId);
        $total = Favorite::where('auction_id', $auctionId)
            ->where('is_liked', 1)
            ->count('auction_id');

        $data = [
            'auction_id' => $auctionId,
            'total_liked' => $total
        ];
        
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function totalLikeOfUser()
    {
        $userId = auth()->user()->user_id;
        $total = Favorite::where('user_id', $userId)
            ->where('is_liked', 1)
            ->count('user_id');

        $data = [
            'total_liked' => $total
        ];
        
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    //accept bid
    public function accept($auctionId, Request $request)
    {
        Auction::findOrFail($auctionId);

        $validator = $this->auctionService->sellingValidation($request->all());

        if ($validator->fails()) {
            $sellingInfo = $validator->errors()->first("selling_info");
            return [
                "code" => 1001,
                "message" => $sellingInfo,
                "data" => null,
            ];
        }

        $userSelling = Auction::where('selling_user_id', auth()->user()->user_id)
            ->where('auction_id', $auctionId)
            ->get()
            ->toArray();

        if ($userSelling) {
            $data = $this->auctionService->accept($request->all(), $auctionId);
            return $data;
        } else {
            return [
                "code" => 1006,
                "message" => "Không có quyền",
                "data" => null,
            ];
        }
    }

    //update status
    public function updateStatus()
    {
        $auctionId = Auction::get()
            ->pluck('auction_id')
            ->toArray();
        return Auction::updateStatus($auctionId);
    }

    //listAuctionByStatus
    public function listAuctionByStatus($statusId, Request $request) 
    {
        $auctions = $this->auctionService->getListAuctionByStatus($statusId, $request->all());
        if ($statusId == 0) {
            $total = Auction::whereIn('status', [1, 2, 3, 6])
                ->count('auction_id');
        } else {
            $total = Auction::where('status', $statusId)
                ->count('auction_id');
        }

        $status = config('const.status');
        $data = [
            'auctions' => $auctions->map(function ($auction) use ($status) {
                $index = $auction->status;
                return [
                    'auction_id' => $auction->auction_id,
                    'selling_user_id' => $auction->selling_user_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'statusId' => $index,
                    'status' => $status[$index],
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image'],
                        'type' => $auction['category']['type'],
                    ],
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

    public function index($statusId, Request $request) {
        if ($request['type']) {
            $typeId = $request['type'];
            $auctions = $this->auctionService->getListAuctionByType($typeId, $statusId, $request->all());

            $categoryId = Category::where('type', $typeId)
                ->get()
                ->pluck('category_id');

            if ($statusId == 0) {
                $total = Auction::whereIn('category_id', $categoryId)
                    ->whereIn('status', [1, 2, 3])
                    ->count('auction_id');
            } else {
                $total = Auction::whereIn('category_id', $categoryId)
                    ->where('status', $statusId)
                    ->count('auction_id');
            }
            
        } elseif ($request['category_id']) {
            $categoryId = $request['category_id'];
            $categoryInfo = Category::where('category_id', $categoryId)
                ->select('name','image', 'type')
                ->get()
                ->first();

            $auctions = $this->auctionService->getListAuctionOfCategory($request->all(), $categoryId, $statusId);
            if ($statusId == 0) {
                $total = Auction::where('category_id', $categoryId)
                    ->whereIn('status', [1, 2, 3])
                    ->count('auction_id');
            } else {
                $total = Auction::where('status', $statusId)
                    ->where('category_id', $categoryId)
                    ->count('auction_id');
            }

            $categoryName = $categoryInfo['name'];
        } elseif ($request['user_id'] && ($request['user_id'] != auth()->user()->user_id)) {
            $userId = $request['user_id'];
            $auctions = $this->auctionService->getListAuctionsByUserK($userId, $statusId, $request->all());
            if ($statusId == 0) {
                $total = Auction::where('selling_user_id', $userId)
                    ->where('status', '<>', 4)
                    ->count('auction_id');
            } else {
                $total = Auction::where('selling_user_id', $userId)
                    ->where('status', $statusId)
                    ->count('auction_id');
            }

            $userInfo = User::where('user_id', $userId)
                ->get()
                ->first();

            $info = [
                'name' => $userInfo->name,
                'avatar' => $userInfo->avatar,
                'phone' => $userInfo->phone,
                'email' => $userInfo->email,
                'role' => $userInfo->role,
                'address' => $userInfo->address ?? '--',
            ];
        } elseif ($request['user_id'] && ($request['user_id'] == auth()->user()->user_id)) {
            $userId = auth()->user()->user_id;
            $auctions = $this->auctionService->getListAuctionsByUser($userId, $statusId, $request->all());
            if ($statusId == 0) {
                $total = Auction::where('selling_user_id', $userId)
                    ->count('auction_id');
            } else {
                $total = Auction::where('selling_user_id', $userId)
                    ->where('status', $statusId)
                    ->count('auction_id');
            }
            $info = [
                'name' => auth()->user()->name,
                'avatar' => auth()->user()->avatar,
                'phone' => auth()->user()->phone,
                'email' => auth()->user()->email,
                'role' => auth()->user()->role,
                'address' => auth()->user()->address ?? '--',
            ];
        } else {
            $auctions = $this->auctionService->getListAuctionByStatus($statusId, $request->all());
            if ($statusId == 0) {
                $total = Auction::where('status', '<>', 4)
                    ->count('auction_id');
            } else {
                $total = Auction::where('status', $statusId)
                    ->count('auction_id');
            }
        }

        $status = config('const.status');
        $type = config('const.categories');

        $data = [
            'auctions' => $auctions->map(function ($auction) use ($status) {
                $index = $auction->status;
                return [
                    'auction_id' => $auction->auction_id,
                    'selling_user_id' => $auction->selling_user_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'statusId' => $index,
                    'status' => $status[$index],
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image'],
                        'type' => $auction['category']['type'],
                    ],
                ];
            }),
            'user_info' => $request['user_id'] ? $info : null,
            'type' => $request['type'] ? $type[$typeId] : null,
            'category' => $request['category_id'] ? $categoryName : null,
            'total' => $total,
        ];

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function info($auctionId) {
        $auction = Auction::findOrFail($auctionId);
        $data = [
            'title' => $auction->title,
            'category_id' => $auction->category_id,
            'start_date' => $auction->start_date,
            'end_date' => $auction->end_date
        ];

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function updateDelivery($auctionId) {
        return $this->auctionService->updateDelivery($auctionId);
    }

    public function rate(Request $request, $auctionId) {
        $userId = auth()->user()->user_id;
        $buyingUserId = Item::where('auction_id', $auctionId)->get()->pluck('buying_user_id')->first();

        if ($userId != $buyingUserId) {
            return [
                "code" => 1006,
                "message" => "Bạn không có quyền đánh giá",
                "data" => null,
            ];
        }

        $validator = $this->auctionService->rateValidation($request->all());
    
        if ($validator->fails()) {
            $star = $validator->errors()->first("star");
            $content = $validator->errors()->first("content");
            return [
                "code" => 1001,
                "message" => "star: " . $star . "&content: " . $content,
                "data" => null,
            ];
        }

        $data = $this->auctionService->rate($request->all(), $auctionId);

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function rateEdit(Request $request, $rateId) {
        $userId = auth()->user()->user_id;
        $buyingUserId = Rate::findOrFail($rateId)->buying_user_id;

        if ($userId != $buyingUserId) {
            return [
                "code" => 1006,
                "message" => "Bạn không có quyền chỉnh sửa đánh giá",
                "data" => null,
            ];
        }

        $validator = $this->auctionService->rateValidation($request->all());
    
        if ($validator->fails()) {
            $star = $validator->errors()->first("star");
            $content = $validator->errors()->first("content");
            return [
                "code" => 1001,
                "message" => "star: " . $star . "&content: " . $content,
                "data" => null,
            ];
        }

        $data = $this->auctionService->rateEdit($request->all(), $rateId);

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function rateInfo($auctionId) {
        $rate = Rate::where('auction_id', $auctionId)
            ->get()
            ->first();

        $data = [
            'content' => $rate->content,
            'star' => $rate->star,
            'image' => $rate->image,
            'updated_at' => $rate->updated_at->format('Y/m/d H:i:s'),
        ];

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }
}
