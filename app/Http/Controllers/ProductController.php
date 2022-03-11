<?php

namespace App\Http\Controllers;

use App\Http\Services\AuctionService;
use App\Http\Services\ItemService;
use App\Models\Item;
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
        return view('categories.list', [
            'title' => '電子に関するオークション一覧',
            'auctionByCategory' => $this->auctionService->getAuctionByCategory($typeId)
        ]);
    }

    public function list($auctionId)
    {
        $itemId = Item::where('auction_id', $auctionId)
            ->get()
            ->pluck('item_id');
            
        return view('categories.listItemOfAuctions', [
            'title' => 'Danh sach item thuoc auction',
            'items' => $this->itemService->getListItemOfAuction($auctionId),
            'images' => $this->itemService->getImageLists($itemId)
        ]);
    }

    public function detail($auctionId) 
    {
        $itemId = Item::where('auction_id', $auctionId)
            ->get()
            ->pluck('item_id');
            
        return view('categories.detail', [
            'title' => '細かいオークション',
            'auction' => $this->auctionService->getDetailAuctions($auctionId),
            'maxPrice' => $this->auctionService->getMaxPrice($auctionId),
            'bids' => $this->auctionService->getBids($auctionId),
            'userSelling' => $this->auctionService->getSellingUser($auctionId),
            'comments' => $this->auctionService->getComments($auctionId),
            'infors' => $this->auctionService->getInfor($auctionId),
            'categoryValueName' => $this->auctionService->getCategoryValueName($auctionId),
            'images' => $this->itemService->getImageLists($itemId)
        ]);
    }
}
