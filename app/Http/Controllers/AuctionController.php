<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class AuctionController extends Controller
{
    protected $auctionService, $categoryService, $itemService;

    public function __construct(AuctionService $auctionService, CategoryService $categoryService, ItemService $itemService)
    {
        $this->auctionService = $auctionService;
        $this->categoryService = $categoryService;
        $this->itemService = $itemService;
    }

    public function list($userId)
    {
        return view('auctions.list', [
            'title' => 'オークション一覧', 
            'auctions' => $this->auctionService->getListAuctions($userId),
            'inforUser' => User::findOrFail($userId),
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

    //delete auction
    public function delete($auctionId)
    {
        $itemId = Item::where('auction_id', '=', $auctionId)
            ->get()
            ->pluck('item_id')
            ->toArray();

        ItemValue::where('item_id', '=', $itemId[0])->delete();
        Item::where('item_id', '=', $itemId[0])->delete();
        Bid::where('auction_id', '=', $auctionId)->delete();
        Comment::where('auction_id', '=', $auctionId)->delete();
        Auction::find($auctionId)->delete();

        return redirect()->route('listAuctions', ['userId' => auth()->user()->user_id])->with('message', 'オークションを削除しました！');
    }
}
