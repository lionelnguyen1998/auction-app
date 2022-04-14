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
    public function notifications()
    {
        $denys = Auction::listDeny();
        $deny = [];
        if ($denys) {
            $deny = [
                'deny' => $denys->map(function ($deny) {
                    return [
                        'title' => $deny->title,
                        'start_date' => $deny->start_date,
                        'end_date' => $deny->end_date,
                        'reason' => $deny->reason
                    ];
                }),
            ];
        }

        $acceptBids = Item::listAccept();
        $accept = [];
        if ($acceptBids) {
            $accept = [
                'accept_bid' => $acceptBids->map(function ($accept) {
                    $auctionId = $accept->auction_id;
                    return $this->auctionService->sellingInfo($auctionId);
                })
            ];
        }
        
        $data = [
            'denys' => $deny,
            'accepts' => $accept,
        ];

        return $this->response->withData($data);
    }

    //list news
    public function news(Request $request)
    {
        $news = News::listNews($request->all());

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
        ];
       
        return $this->response->withData($data);
    }

    //read report reject and selling_info auction
    public function reason($auctionId)
    {
        $is_read = UserReadNews::where('auction_id', $auctionId)
            ->get();
        
        if (empty($is_read[0])) {
            $is_read = UserReadNews::insert([
                'auction_id' => $auctionId,
                'is_read' => true,
                'new_id' => null,
            ]);
        }

        return $this->response->withData($is_read);
    }

    //read news
    public function read($newId)
    {
        $is_read = UserReadNews::where('new_id', $newId)
            ->get();

        if (empty($is_read[0])) {
            $is_read = UserReadNews::insert([
                'auction_id' => null,
                'is_read' => true,
                'new_id' => $newId,
            ]);
        }
    
        return $this->response->withData($is_read);
    }

    //read news
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
