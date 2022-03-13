<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\AuctionService;
use App\Http\Services\CategoryService;
use App\Http\Services\ItemService;
use App\Models\Auction;
use App\Models\Slider;
use App\Models\AuctionStatus;

class AuctionController extends Controller
{
    protected $auctionService, $categoryService, $itemService;

    public function __construct(AuctionService $auctionService, CategoryService $categoryService, ItemService $itemService)
    {
        $this->auctionService = $auctionService;
        $this->categoryService = $categoryService;
        $this->itemService = $itemService;
    }

    public function list()
    {
        return view('auctions.list', [
            'title' => 'オークション一覧', 
            'auctions' => $this->auctionService->getListAuctions(),
            'logo' => Slider::logo(),
        ]);
    }
    
    //create auction
    public function create()
    {
        return view('auctions.create', [
            'title' => 'オークション追加',
            'category' => $this->categoryService->getCategoryList(),
            'logo' => Slider::logo(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->auctionService->auctionValidation($request->all());

        if ($validated->fails()) {
            return redirect(url()->previous())
                ->withErrors($validated)
                ->withInput();
        }

        $auction = Auction::create([
            'category_id' => $request['category_id'],
            'selling_user_id' => (int)$request['selling_user_id'] ?? null,
            'title' => $request['title_ni'],
            'title_en' => $request['title_en'] ?? null,
            'description' => $request['description'] ?? null,
            'start_date' => date('Y/m/d H:i', strtotime($request['start_date'])),
            'end_date' => date('Y/m/d H:i', strtotime($request['end_date']))
        ]);

        $auctionStatus = AuctionStatus::create([
            'auction_id' => $auction->auction_id,
            'status' => 4
        ]);

        return redirect()->route('createItem', ['auctionId' => $auction->auction_id, 'categoryId' => $auction->category_id]);
    }

    //accept bid when auction end
    public function acceptBid($bidId) 
    {
        dd($bidId);
    }

    //report deny with client
    public function deny()
    {
        dump($this->auctionService->deny());
        return view('auctions.report', [
            'title' => 'お知らせ',
            'auction' => $this->auctionService->deny(),
            'logo' => Slider::logo(),
        ]);
    }
}
