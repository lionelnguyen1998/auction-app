<?php

namespace App\Http\Services;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Category;
use App\Models\CategoryValue;
use App\Models\ItemValue;
use App\Models\AuctionStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuctionService implements AuctionServiceInterface
{
    const LIMIT = 16;

    public function getDetailAuctions($auctionId)
    {
        $auctions = Auction::with('category', 'auctionStatus', 'items', 'comments')
            ->where('auction_id', $auctionId)
            ->get()
            ->toArray();
        return $auctions;
    }

    public function getSellingUser($auctionId)
    {
        $userSelling = Item::with('users')
            ->where('auction_id', $auctionId)
            ->get()
            ->toArray();
        return $userSelling;
    }

    public function getMaxPrice($auctionId) 
    {
        $price = Bid::where('auction_id', $auctionId)
            ->max('price');
        return $price;
    }

    public function getBids($auctionId) 
    {
        $bids = Bid::with('users')
            ->where('auction_id', $auctionId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->toArray();
        return $bids;
    }

    public function getComments($auctionId) 
    {
        $comments = Comment::with('users')
            ->where('auction_id', $auctionId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->toArray();
        return $comments;
    }

    public function getInfor($auctionId)
    {
        $categoryId = Auction::findOrFail($auctionId)->category_id;

        $itemId = Item::where('auction_id', $auctionId)
            ->where('category_id', $categoryId)
            ->get()
            ->pluck('item_id');    
        $itemInfor = ItemValue::where('item_id', $itemId)
            ->get()
            ->pluck('value', 'category_value_id')
            ->toArray();

        return $itemInfor;
    }

    // value of category
    public function getCategoryValueName($auctionId)
    {
        $categoryId = Auction::findOrFail($auctionId)->category_id;

        $categoryValue = CategoryValue::where('category_id', $categoryId)
            ->get()
            ->pluck('name', 'category_value_id')
            ->toArray();

        return $categoryValue;
    }

    //general auction
    public function getGeneralInfo()
    {
        $countAuction = Auction::count('auction_id');

        $status1 = AuctionStatus::where('status', 1)
            ->count('auction_id');
        $status2 = AuctionStatus::where('status', 2)
            ->count('auction_id');
        $status4 = AuctionStatus::where('status', 4)
            ->count('auction_id');

        $auctionInfo = [
            'all' => $countAuction,
            'status1' => $status1,
            'status2' => $status2,
            'status4' => $status4
        ];

        return $auctionInfo;
    }

    //get auction by categoryId and typeCategory
    public function getAuctionByCategory($typeId)
    {
        $categoryId = Category::where('type', $typeId)
            ->get()
            ->pluck('category_id')
            ->toArray();

        $auction = Auction::with('category', 'items', 'auctionStatus')
            ->whereIn('category_id', $categoryId)
            ->get()
            ->toArray();

        return $auction;
    }

    //get list auctions
    public function getListAuction()
    {
        $auction = Auction::with('category', 'items', 'auctionStatus')
            ->get()
            ->toArray();

        return $auction;
    }

    //validate create auction 
    public function auctionValidation($request)
    {
        $rules = [
            'category_id' => "required",
            'title_ni' => "required|max:255|unique:auctions,title",
            'start_date' => "required|date|after_or_equal:tomorrow",
            'end_date' => "required|date|after:start_date"
        ];

        $messages = [
            'required' => '必須項目が未入力です。',
            'max' => ':max文字以下入力してください。 ',
            'date' => 'データのフォーマットが正しくありません',
            'after_or_equal' => '始まる時間が明日か行かなければなりません',
            'after' => '始まる時間よりです。',
            'unique' => '既に使用されています。'
        ];

        $attributes = [
            'category_id' => 'カテゴリー',
            'title_ni' => 'オークション名',
            'start_date' => '始まる時間',
            'end_date' => '終わる時間'
        ];

        $validated = Validator::make($request, $rules, $messages, $attributes);

        return $validated;
    }

    public function deny()
    {
        $userId = auth()->user()->user_id;
        $auction = Auction::withTrashed()
            ->with('auctionDeny')
            ->where('selling_user_id', $userId)
            ->get()
            ->toArray();
        
        return $auction;
    }

    public function get($page = null)
    {
        return Auction::orderByDesc('auction_id')
            ->when($page != null, function ($query) use ($page) {
                $query->offset($page * self::LIMIT);
            })
            ->limit(self::LIMIT)
            ->get();
    }

    public function getListAuctions($userId)
    {
        $list = Auction::with('category', 'items', 'auctionStatus')
            ->where('selling_user_id', $userId)
            ->get()
            ->toArray();

        return $list;
    }
}
