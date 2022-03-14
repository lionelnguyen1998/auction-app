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
        $auctions = Auction::all();

        return [
            'auctions' => $auctions->map(function ($auction) {
                return [
                    'auction_id' => $auction->auction_id,
                    'category_id' => $auction->category_id,
                    'selling_user_id' => $auction->selling_user_id,
                    'title' => $auction->title,
                    'title_en' => $auction->title_en,
                    'description' => $auction->description,
                    'start_date' => $auction->start_date,
                    'end_date' => $auction->end_date,
                    'created_at' => $auction->created_at->format('Y/m/d H:i:s'),
                    'updated_at' => $auction->updated_at->format('Y/m/d H:i:s'),
                ];
            }),
        ];
    }
}
