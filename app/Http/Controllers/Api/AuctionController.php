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

    //all auction in home page
    public function index(Request $request)
    {
        $auctions = $this->auctionService->getListAuction($request->all());

        $total = Auction::where('status', '<>', 4)
            ->count('auction_id');

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

    //detail auctions
    public function detail($auctionId)
    {
        $itemId = Item::where('auction_id', $auctionId)
            ->get()
            ->pluck('item_id');

        $auction = $this->auctionService->getDetailAuctions($auctionId);
        $maxPrice = $this->auctionService->getMaxPrice($auctionId);
        $bids = $this->auctionService->getBids($auctionId);
        $comments = $this->auctionService->getComments($auctionId);
        $images = $this->itemService->getImageLists($itemId);
        $brand = Brand::where('brand_id', $auction[0]['items']['brand_id'])
            ->get()
            ->pluck('name')
            ->firstOrFail();
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
                'like' => auth()->user() ? $auction[0]['like']['is_liked'] : null
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
                'mainImage' => $images[0],
                'images' => $images,
            ],
            'selling_user' => [
                'selling_user_id' => $auction[0]['user_selling']['user_id'],
                'selling_user_name' => $auction[0]['user_selling']['name'],
                'selling_user_avatar' => $auction[0]['user_selling']['avatar']
            ],
            'max_bid' => $maxPrice,
            'bids' => $bids->map(function ($bid) {
                return [
                    'price' => $bid->price,
                    'created_at' => $bid->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $bid->updated_at->format('Y/m/d H:i:s'),
                    'user_name' => $bid['users']['name'],
                    'user_avatar' => $bid['users']['avatar']
                ];
            }),
            'comments' => $comments->map(function ($comment) {
                return [
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $comment->updated_at->format('Y/m/d H:i:s'),
                    'user_name' => $comment['users']['name'],
                    'user_avatar' => $comment['users']['avatar']
                ];
            }),

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

    public function listAuctionByType($typeId, Request $request)
    {
        $auctions = $this->auctionService->getListAuctionByType($typeId, $request->all());
        $categoryId = Category::where('type', $typeId)
            ->get()
            ->pluck('category_id');
            
        $total = Auction::where('category_id', $categoryId)
            ->count('auction_id');

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

    public function listAuctionOfCategory(Request $request)
    {
        $auctions = $this->auctionService->getListAuctionOfCategory($request->all());
        $statusId = $request['status_id'];
        $categoryId = $request['category_id'];
        if ($statusId == 0) {
            $total = Auction::where('category_id', $categoryId)
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
            if ($auction && ($value->status != 4) && ($value->status != 6)) {
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
        $data = $this->auctionService->comments($auctionId, $request->all());

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
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
        $userId = auth()->user()->user_id;
        $userCommentId = Comment::where('comment_id', $commentId)
            ->get()
            ->pluck('user_id')
            ->first();
        
        if ($userId != $userCommentId) {
            return [
                "code" => 1006,
                "message" => "Khong co quyen",
                "data" => null,
            ];
        } else {
            $comment->delete();
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => 'Đã xóa comment',
            ];
        }

    }

    //create bids
    public function bids($auctionId, Request $request)
    {
        $validator = $this->auctionService->bidValidation($request->all(), $auctionId);

        if ($validator->fails()) {
            $price = $validator->errors()->first("price");
            return [
                "code" => 1001,
                "message" => "price: " . $price,
                "data" => null,
            ];
        }

        $data = $this->auctionService->bids($auctionId, $request->all());

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
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
            ->orderBy('created_at', 'DESC')
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
            $total = Auction::where('status', '<>', 4)
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
}
