<?php

namespace App\Http\Controllers;

use App\Http\Services\AuctionService;
use App\Http\Services\ItemService;
use App\Models\Item;
use App\Models\Slider;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $auctionService, $itemService;

    public function __construct(AuctionService $auctionService, ItemService $itemService)
    {
        $this->auctionService = $auctionService;
        $this->itemService = $itemService;
    }

    public function index($typeId)
    {
        $all = config('const.categories');
        $name = $all[$typeId];

        return view('categories.list', [
            'title' => $name,
            'auctionByCategory' => $this->auctionService->getAuctionByCategory($typeId),
            'slider' => Slider::where('type', $typeId)->get()->toArray(),
            'type' => $name,
            'logo' => Slider::logo(),
        ]);
    }

    public function list($auctionId)
    {
        $itemId = Item::where('auction_id', $auctionId)
            ->get()
            ->pluck('item_id');

        return view('categories.listItemOfAuctions', [
            'title' => 'アイテムの情報',
            'items' => $this->itemService->getListItemOfAuction($auctionId),
            'images' => $this->itemService->getImageLists($itemId),
            'logo' => Slider::logo(),
        ]);
    }

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
        $brand = Brand::where('brand_id', $auction[0]['items']['brand_id'])
            ->get()
            ->pluck('name')
            ->firstOrFail();
        $status = config('const.status');
        $index = $auction[0]['status'];
        $logo = Slider::logo();

        return view('categories.detail', [
            'logo' => $logo,
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

        ]);
    }
}
