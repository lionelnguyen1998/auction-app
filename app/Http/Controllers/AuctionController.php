<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\AuctionService;
use App\Http\Services\CategoryService;
use App\Models\Auction;
use App\Models\AuctionStatus;

class AuctionController extends Controller
{
    protected $auctionService, $categoryService;

    public function __construct(AuctionService $auctionService, CategoryService $categoryService)
    {
        $this->auctionService = $auctionService;
        $this->categoryService = $categoryService;
    }
    
    //create auction
    public function create()
    {
        return view('auctions.create', [
            'title' => 'オークション追加',
            'category' => $this->categoryService->getCategoryList()
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
            'title' => 'Thong bao',
            'auction' => $this->auctionService->deny()
        ]);
    }
}
