<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Models\News;
use App\Models\Bid;
use App\Models\Item;
use App\Models\User;
use App\Models\Auction;
use App\Models\UserReadNews;
use App\Http\Services\AuctionService;

class NewController extends ApiController
{
    protected $auctionService;

    public function __construct(Request $request, ApiResponse $response, AuctionService $auctionService)
    {
        $this->auctionService = $auctionService;
        parent::__construct($request, $response);
    }

    //list notifications
    public function notifications(Request $request)
    {
        $denys = Auction::listDeny($request);

        $total = Auction::withTrashed()
            ->where('selling_user_id', auth()->user()->user_id)
            ->where('status', '=', 5)
            ->count('auction_id');

        if ($denys) {
            $data = [
                'denys' => $denys->map(function ($deny) {
                    return [
                        'title' => $deny->title,
                        'start_date' => $deny->start_date,
                        'end_date' => $deny->end_date,
                        'reason' => $deny->reason
                    ];
                }),
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
                return [
                    'user_id' => $new->user_id,
                    'title' => $new->title,
                    'content' => $new->content,
                    'created_at' => $new->created_at->format('Y/m/d H:i'),
                    'updated_at' => $new->updated_at->format('Y/m/d H:i')
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
        $is_read = UserReadNews::where('auction_id', $auctionId)
            ->get()
            ->first();
        
        if (empty($is_read)) {
            UserReadNews::insert([
                'auction_id' => $auctionId,
                'is_read' => true,
            ]);

            $data = [
                'is_read' => 1,
                'auction_id' => $auctionId
            ];
        } else {
            $data = [
                'is_read' => $is_read->is_read,
                'auction_id' => $is_read->auction_id
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
        News::findOrFail($newId);
        $is_read = UserReadNews::where('new_id', $newId)
            ->get()
            ->first();

        if (empty($is_read)) {
            UserReadNews::insert([
                'is_read' => true,
                'new_id' => $newId,
            ]);

            $data = [
                'is_read' => 1,
                'new_id' => $newId,
            ];
        } else {
            $data = [
                'is_read' => $is_read->is_read,
                'new_id' => $is_read->new_id,
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
}
