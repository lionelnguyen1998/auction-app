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
        ];
        return $this->response->withData($data);
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

        return [
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
                'mainImage' => $images[0],
                'images' => $images->map(function ($image) {
                    return [
                        'image' => $image,
                    ];
                })
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
        return $this->response->withData($data);
    }

    public function listAuctionsByUser($userId, Request $request)
    {
        $auctions = $this->auctionService->getListAuctionsByUser($userId, $request->all());
        $total = Auction::where('selling_user_id', $userId)
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
        return $this->response->withData($data);
    }

    //create auctions
    public function create(Request $request)
    {
        $validator = $this->auctionService->auctionValidation($request->all());

        if ($validator->fails()) {
            return $this->response->errorValidation($validator);
        }

        $data = $this->auctionService->create($request->all());
        return $this->response->withData($data);
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

        if ($status == 4) {
            $validator = $this->auctionService->auctionValidationEdit($request->all(), $auctionId);
    
            if ($validator->fails()) {
                return $this->response->errorValidation($validator);
            }

            $data = $this->auctionService->edit($auctionId, $request->all());
            return $this->response->withData($data);
        } else {
            $message = "Khong the chinh sua";
            return $this->response->withData($message);
        } 
    }

    //create comment
    public function comments($auctionId, Request $request) 
    {
        $data = $this->auctionService->comments($auctionId, $request->all());

        return $this->response->withData($data);
    }

    //list comment
    public function listComments($auctionId, Request $request)
    {
        $page = $request->index;
        $perPage = $request->count;

        $comments = Comment::where('auction_id', $auctionId)
            ->orderBy('created_at', 'DESC')
            ->forPage($page, $perPage)
            ->get();

        $total = Comment::where('auction_id', $auctionId)
            ->count('comment_id');

        $data = [
            'comments' => $comments->map(function($comment) {
                return [
                    'user_id' => $comment->user_id,
                    'content' => $comment->content,
                    'updated_at' => $comment->updated_at->format('Y/m/d H:i:s'),
                ];
            }),
            'total' => $total
        ];

        return $this->response->withData($data);
    }

    //create bids
    public function bids($auctionId, Request $request)
    {
        $validator = $this->auctionService->bidValidation($request->all(), $auctionId);

        if ($validator->fails()) {
            return $this->response->errorValidation($validator);
        }

        $data = $this->auctionService->bids($auctionId, $request->all());

        return $this->response->withData($data);
    }

    //list bids
    public function listBids($auctionId, Request $request)
    {
        $page = $request->index;
        $perPage = $request->count;

        $total = Bid::where('auction_id', $auctionId)
            ->count('bid_id');

        $bids = Bid::where('auction_id', $auctionId)
            ->orderBy('created_at', 'DESC')
            ->forPage($page, $perPage)
            ->get();

        $data = [
            'bids' => $bids->map(function($bid) {
                return [
                    'user_id' => $bid->user_id,
                    'price' => $bid->price,
                    'updated_at' => $bid->updated_at->format('Y/m/d H:i:s'),
                ];
            }),
            'total' => $total
        ];

        return $this->response->withData($data);
    }

    //update like
    public function updateLike(Request $request)
    {
        $userId = auth()->user()->user_id;
        $auctionId = $request['auction_id'];
        
        $validated = $this->auctionService->likeValidation($request->all());

        if ($validated->fails()) {
            return redirect(url()->previous())
                ->withErrors($validated)
                ->withInput();
        }

        $checkLiked = Favorite::where('auction_id', $auctionId)
            ->get()
            ->toArray();

        if ($checkLiked) {
            $is_liked = Favorite::where('auction_id', $auctionId)
                ->get()
                ->firstOrFail();

            $is_liked->timestamps = false;
            $is_liked->is_liked = !($is_liked->is_liked);
            $is_liked->update();
        } else {
            $is_liked = Favorite::insert([
                'user_id' => auth()->user()->user_id,
                'auction_id' => $auctionId ?? null,
                'is_liked' => true
            ]);
        }

        $data = [
            'user_id' => $is_liked->user_id,
            'auction_id' => $is_liked->auction_id,
            'is_liked' => $is_liked->is_liked
        ];

        return $this->response->withData($data);
    }

    public function listLikes(Request $request) {
        $userId = auth()->user()->user_id;
        $auctions = $this->auctionService->getListAuctionLike($request->all());
        $total = Favorite::where('user_id', $userId)->count('auction_id');
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
        return $this->response->withData($data);
    }

    //accept bid
    public function accept($auctionId, Request $request)
    {
        $userSelling = Auction::where('selling_user_id', auth()->user()->user_id)
            ->where('auction_id', $auctionId)
            ->get()
            ->toArray();

        if ($userSelling) {
            $data = $this->auctionService->accept($request->all(), $auctionId);
            return $this->response->withData($data);
        } else {
            return [
                'message' => 'khong co quyen'
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
        $total = Auction::where('status', $statusId)
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
        return $this->response->withData($data);
    }
}
