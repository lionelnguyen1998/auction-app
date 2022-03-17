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
use App\Models\ItemValue;
use App\Models\Comment;
use App\Models\AuctionStatus;

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

    //API
    //all auction in home page
    public function index()
    {
        $auctions = $this->auctionService->getListAuction();
        return [
            'auctions' => $auctions->map(function ($auction) {
                return [
                    'auction_id' => $auction->auction_id,
                    'selling_user_id' => $auction->selling_user_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'created_at' => $auction->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $auction->updated_at->format('Y/m/d H:i:s'),
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image'],
                        'type' => $auction['category']['type'],
                    ],
                    'status' => $auction['auctionStatus']['status'],
                ];
            }),
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
        $itemValue = $this->itemService->getInfor($itemId);
        $images = $this->itemService->getImageLists($itemId);
        $logo = Slider::logo();

        return [
            'logo' => $logo,
            'auctions' => [
                'auction_id' => $auction[0]['auction_id'],
                'title' => $auction[0]['title'],
                'start_date' => $auction[0]['start_date'],
                'end_date' => $auction[0]['end_date'],
                'status' => $auction[0]['auction_status']['status'],
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
                'brand_id' => $auction[0]['items']['brand_id'],
                'series' => $auction[0]['items']['series'],
                'description' => $auction[0]['items']['description'],
                'starting_price' => $auction[0]['items']['starting_price'],
                'images' => $images->map(function ($image) {
                    return [
                        'image' => $image,
                    ];
                }),
                'values' => $itemValue->map(function ($value) {
                    return [
                        $value['categoryValues']['name'] => $value['value'],
                    ];
                }),
            ],
            'selling_user' => [
                'selling_user_id' => $auction[0]['user_selling']['user_id'],
                'selling_user_name' => $auction[0]['user_selling']['name'],
                'selling_user_avatar' => $auction[0]['user_selling']['avatar']
            ],
            'max_bid' => $maxPrice,
            'bids' => $bids->map(function ($bid) {
                return [
                    'bid_id' => $bid->bid_id,
                    'price' => $bid->price,
                    'created_at' => $bid->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $bid->updated_at->format('Y/m/d H:i:s'),
                    'user' => $bid['users']['name'],
                    'user_avatar' => $bid['users']['avatar']
                ];
            }),
            'comments' => $comments->map(function ($comment) {
                return [
                    'comment_id' => $comment->comment_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $comment->updated_at->format('Y/m/d H:i:s'),
                    'user' => $comment['users']['name'],
                    'user_avatar' => $comment['users']['avatar']
                ];
            }),

        ];
    }

    public function listAuctionByType($typeId)
    {
        $auctions = $this->auctionService->getListAuctionByType($typeId);
        return [
            'auctions' => $auctions->map(function ($auction) {
                return [
                    'auction_id' => $auction->auction_id,
                    'selling_user_id' => $auction->selling_user_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'created_at' => $auction->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $auction->updated_at->format('Y/m/d H:i:s'),
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image'],
                        'type' => $auction['category']['type'],
                    ],
                    'status' => $auction['auctionStatus']['status'],
                ];
            }),
        ];
    }

    public function listAuctionsByUser($userId)
    {
        $auctions = $this->auctionService->getListAuctionsByUser($userId);
        return [
            'auctions' => $auctions->map(function ($auction) {
                return [
                    'auction_id' => $auction->auction_id,
                    'selling_user_id' => $auction->selling_user_id,
                    'title' => $auction->title,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'created_at' => $auction->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $auction->updated_at->format('Y/m/d H:i:s'),
                    'category' => [
                        'name' => $auction['category']['name'],
                        'image' => $auction['category']['image'],
                        'type' => $auction['category']['type'],
                    ],
                    'status' => $auction['auctionStatus']['status'],
                ];
            }),
        ];
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
        $status = AuctionStatus::where('auction_id', '=', $auctionId)
            ->get()
            ->pluck('status');
        if ($status[0] == 3) {
            $itemId = Item::where('auction_id', '=', $auctionId)
                ->get()
                ->pluck('item_id')
                ->toArray();
    
            if (isset($itemId[0])) {
                ItemValue::where('item_id', '=', $itemId[0])->delete();
                Item::where('item_id', '=', $itemId[0])->delete();
                Bid::where('auction_id', '=', $auctionId)->delete();
                Comment::where('auction_id', '=', $auctionId)->delete();
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
        $status = AuctionStatus::where('auction_id', $auctionId)
            ->get()
            ->pluck('status');
        
        if ($status[0] == 4) {
            $validator = $this->auctionService->auctionValidation($request->all());
    
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
}
