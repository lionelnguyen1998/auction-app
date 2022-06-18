<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiResponse;
use App\Models\News;
use App\Models\Auction;
use App\Models\Item;
use App\Models\UserReadNews;
use App\Http\Services\AuctionService;

class SearchController extends ApiController
{
    protected $auctionService;

    public function __construct(Request $request, ApiResponse $response, AuctionService $auctionService)
    {
        $this->auctionService = $auctionService;
        parent::__construct($request, $response);
    }

    public function search(Request $request) 
    {
        $type = $request['type'];
        $key = $request['key'];

        //theo gia khoi diem
        if ($type == 1) {
            $result = Item::join('auctions', 'auctions.auction_id', '=', 'items.auction_id')
                ->where('status', '<>', 4)
                ->where('items.starting_price', 'LIKE', '%'.$key.'%')
                ->select('items.auction_id', 'items.name', 'items.starting_price')
                ->get();

            if (count($result) && $key) {
                return [
                    "code" => 1000,
                    "message" => "OK",
                    "data" => $result->map(function($result) {
                        return [
                            'id' => $result->auction_id,
                            'name' => $result->name,
                            'key' => number_format($result->starting_price) . ' $',
                        ];
                    }),
                ];
            } else {
                return [
                    "code" => 9998,
                    "message" => "Khong tim thay",
                    "data" => null,
                ];
            }
        } else if ($type == 2) {
            //thoi gian bat dau
            $result = Auction::where('start_date', 'LIKE', '%'.$key.'%')
                ->where('status', '<>', 4)
                ->select('auction_id', 'title', 'start_date')
                ->get();

            if (count($result) && $key) {
                return [
                    "code" => 1000,
                    "message" => "OK",
                    "data" => $result->map(function($result) {
                        return [
                            'id' => $result->auction_id,
                            'name' => $result->title,
                            'key' => $result->start_date,
                        ];
                    }),
                ];
            } else {
                return [
                    "code" => 9998,
                    "message" => "Khong tim thay",
                    "data" => null,
                ];
            }
        } else if ($type == 3) {
            //thoi gian ket thuc
            $result = Auction::where('end_date', 'LIKE', '%'.$key.'%')
                ->where('status', '<>', 4)
                ->select('auction_id', 'title', 'end_date')
                ->get();

            if (count($result) && $key) {
                return [
                    "code" => 1000,
                    "message" => "OK",
                    "data" => $result->map(function($result) {
                        return [
                            'id' => $result->auction_id,
                            'name' => $result->title,
                            'key' => $result->end_date,
                        ];
                    }),
                ];
            } else {
                return [
                    "code" => 9998,
                    "message" => "Khong tim thay",
                    "data" => null,
                ];
            }
        } else {
            //ten auctions
            $result = Auction::where('title', 'LIKE', '%'.$key.'%')
                ->where('status', '<>', 4)
                ->select('auction_id', 'title')
                ->get();

            if (count($result) && $key) {
                return [
                    "code" => 1000,
                    "message" => "OK",
                    "data" => $result->map(function($result) {
                        return [
                            'id' => $result->auction_id,
                            'name' => $result->title,
                            'key' => $result->null,
                        ];
                    }),
                ];
            } else {
                return [
                    "code" => 9998,
                    "message" => "検索できません",
                    "data" => null,
                ];
            }
        }
    } 
}
