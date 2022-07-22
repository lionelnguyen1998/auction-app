<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Models\News;
use App\Models\Bid;
use App\Models\Item;
use App\Models\User;
use App\Models\Brand;
use App\Models\Auction;
use App\Models\Image;
use App\Models\UserReadNews;
use App\Http\Services\AuctionService;
use App\Http\Services\ItemService;

class NewController extends ApiController
{
    protected $auctionService, $itemService;

    public function __construct(Request $request, ApiResponse $response, AuctionService $auctionService, ItemService $itemService)
    {
        $this->auctionService = $auctionService;
        $this->itemService = $itemService;
        parent::__construct($request, $response);
    }

    //list notifications
    public function notifications(Request $request)
    {
        $userId = auth()->user()->user_id;
        $isNotRead = $request->is_not_read;
        $denys = Auction::listDeny($request, $userId, $isNotRead);

        $total = Auction::withTrashed()
            ->where('selling_user_id', $userId)
            ->where('status', '=', 5)
            ->count('auction_id');

        
        $auctionDenyId = Auction::withTrashed()
            ->where('selling_user_id', $userId)
            ->where('status', '=', 5)
            ->get()
            ->pluck('auction_id');

        $isRead = UserReadNews::whereIn('auction_id', $auctionDenyId)
            ->where('user_id', $userId)
            ->where('is_read', 1)
            ->get()
            ->count('user_read_new_id');

        $notRead = $total - $isRead;
        // type 2 reject, type 1 accept bid
        if ($denys) {
            $data = [
                'denys' => $denys->map(function ($deny) use ($userId) {
                    return [
                        'auction_id' => $deny->auction_id,
                        'title' => $deny->title,
                        'start_date' => $deny->start_date,
                        'end_date' => $deny->end_date,
                        'reason' => $deny->reason,
                        'updated_at' => $deny->updated_at->format('Y/m/d H:i:s'),
                        'type' => 2,
                        'is_read' => UserReadNews::isRead($deny->auction_id, $userId)
                    ];
                }),
                'total_not_read' => $notRead,
                'total' => $total
            ];

            return [
                "code" => 1000,
                "message" => "OK",
                "data" => $data,
            ];
        } else {
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => null,
            ];
        }
    }

    //list news
    public function news(Request $request)
    {
        $news = News::listNews($request->all());

        $total = News::all()->count('new_id');

        $data = [
            'news' => $news->map(function ($new) {
                $userCreate = User::findOrFail($new->user_id)->name;
                if (auth()->user()) {
                    $isRead = UserReadNews::where('new_id', $new->new_id)
                        ->where('user_id', auth()->user()->user_id)
                        ->where('is_read', 1)
                        ->get()
                        ->pluck('is_read')
                        ->first();
                }

                return [
                    'user' => $userCreate,
                    'new_id' => $new->new_id,
                    'title' => $new->title,
                    'content' => $new->content,
                    'updated_at' => $new->updated_at->format('Y/m/d H:i'),
                    'is_read' => $isRead ?? null,
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

    //read report reject and selling_info auction
    public function reason($auctionId)
    {
        $checkAuction = Auction::withTrashed()
            ->where('status', 5)
            ->findOrFail($auctionId);
      
        if (auth()->user()->user_id != $checkAuction->selling_user_id) {
            return [
                "code" => 1006,
                "message" => "Không có quyền",
                "data" => null,
            ];
        }
        
        $is_read = UserReadNews::where('auction_id', $auctionId)
            ->where('user_id', auth()->user()->user_id)
            ->get()
            ->first();

        $itemId = Item::withTrashed()
            ->where('auction_id', $auctionId)
            ->get()
            ->pluck('item_id')
            ->first();

        if ($itemId) {
            $itemInfo = Item::withTrashed()
                ->where('auction_id', $auctionId)
                ->get()
                ->first();

            $images = Image::withTrashed()
                ->where('item_id', $itemId)
                ->get()
                ->pluck('image');

            $brand = Brand::where('brand_id', $itemInfo['brand_id'])
                ->get()
                ->pluck('name')
                ->first();
        }

        $auction = Auction::withTrashed()
            ->with('category')
            ->where('auction_id', $auctionId)
            ->get()
            ->first();
            
        
        $status = config('const.status');
        $index = $auction['status'];

        $total = Auction::withTrashed()
            ->where('selling_user_id', auth()->user()->user_id)
            ->where('status', '=', 5)
            ->count('auction_id');
            
        $auctionDenyId = Auction::withTrashed()
            ->where('selling_user_id', auth()->user()->user_id)
            ->where('status', '=', 5)
            ->get()
            ->pluck('auction_id');

        $isRead = UserReadNews::whereIn('auction_id', $auctionDenyId)
            ->where('user_id', auth()->user()->user_id)
            ->where('is_read', 1)
            ->get()
            ->count('user_read_new_id');

        $notRead = $total - $isRead;

        if (empty($is_read)) {
            UserReadNews::insert([
                'auction_id' => $auctionId,
                'is_read' => true,
                'user_id' => auth()->user()->user_id
            ]);
        }

        if ($itemId) {
            $data = [
                'is_read' => 1,
                'auction_id' => $auctionId,
                'type' => 2,
                'auctions' => [
                    'title' => $auction['title'],
                    'start_date' => $auction['start_date'],
                    'end_date' => $auction['end_date'],
                    'status' => $status[$index],
                    'statusId' => $auction['status'],
                    'updated_at' => $auction['updated_at'],
                    'reason' => $auction['reason'],
                ],
                'category' => [
                    'name' => $auction['category']['name']
                ],
                'items' => [
                    'name' => $itemInfo['name'],
                    'brand' => $brand,
                    'series' => $itemInfo['series'],
                    'description' => $itemInfo['description'],
                    'starting_price' => $itemInfo['starting_price'],
                    'mainImage' => $images,
                    'images' => $images,
                ],
                'total_not_read' => $notRead,
                'total' => $total
            ];
        } else {
            $data = [
                'is_read' => 1,
                'auction_id' => $auctionId,
                'type' => 2,
                'auctions' => [
                    'title' => $auction['title'],
                    'start_date' => $auction['start_date'],
                    'end_date' => $auction['end_date'],
                    'status' => $status[$index],
                    'statusId' => $auction['status'],
                    'updated_at' => $auction['updated_at'],
                    'reason' => $auction['reason'],
                ],
                'category' => [
                    'name' => $auction['category']['name']
                ],
                'items' => null,
                'total_not_read' => $notRead,
                'total' => $total
            ];
        }

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    //read news
    public function read($newId)
    {
        $news = News::findOrFail($newId);
        if (auth()->user()) {
            $userId = auth()->user()->user_id;
            $is_read = UserReadNews::where('new_id', $newId)
                ->where('user_id', $userId)
                ->get()
                ->first();
            $useName = User::findOrFail($news->user_id)->name;

            if (empty($is_read)) {
                UserReadNews::insert([
                    'is_read' => true,
                    'new_id' => $newId,
                    'user_id' => $userId
                ]);

                $data = [
                    'is_read' => 1,
                    'new_id' => $newId,
                    'content' => $news->content,
                    'user_id' => $userId,
                    'title' => $news->title,
                    'updated_at' => $news->updated_at,
                    'user_create_name' => $useName
                ];
            } else {
                $data = [
                    'is_read' => $is_read->is_read,
                    'new_id' => $is_read->new_id,
                    'content' => $news->content,
                    'user_id' => $userId,
                    'title' => $news->title,
                    'updated_at' => $news->updated_at->format('Y/m/d'),
                    'user_create_name' => $useName
                ];
            }
        } else {
            $useName = User::findOrFail($news->user_id)->name;
            $data = [
                'is_read' => null,
                'new_id' => $news->new_id,
                'content' => $news->content,
                'user_id' => null,
                'title' => $news->title,
                'updated_at' => $news->updated_at->format('Y/m/d'),
                'user_create_name' => $useName
            ];
        }
    
        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    //read news accept auctions
    public function readAccept($itemId)
    {
        $is_read = UserReadNews::where('item_id', $itemId)
            ->get();

        if (empty($is_read[0])) {
            $is_read = UserReadNews::insert([
                'auction_id' => null,
                'is_read' => true,
                'item_id' => $itemId,
            ]);
        }
    
        return $this->response->withData($is_read);
    }

    //delete notifications 
    public function deleteNotification($auctionId) {
        $userId = auth()->user()->user_id;

        $auction = Auction::withTrashed()
            ->where('auction_id', $auctionId)
            ->where('selling_user_id', $userId)
            ->where('status', 5)
            ->get()
            ->first();

        $itemId = Item::withTrashed()
            ->where('auction_id', $auctionId)
            ->where('selling_user_id', $userId)
            ->get()
            ->pluck('item_id')
            ->first();

        if ($auction) {
            UserReadNews::where('auction_id', $auctionId)->forceDelete();
            Item::where('item_id', $itemId)->forceDelete();
            Image::where('item_id', $itemId)->forceDelete();
            Auction::where('auction_id', $auctionId)->forceDelete();
            $total = Auction::withTrashed()
                ->where('selling_user_id', $userId)
                ->where('status', '=', 5)
                ->count('auction_id'); 
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => [
                    'total' => $total
                ],
            ];
        } else {
            return [
                "code" => 1006,
                "message" => "Không có quyền",
                "data" => null,
            ];
        }
    }
}
