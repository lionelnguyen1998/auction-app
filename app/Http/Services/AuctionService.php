<?php

namespace App\Http\Services;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Image;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryValue;
use App\Models\ItemValue;
use App\Models\AuctionStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuctionService implements AuctionServiceInterface
{
    const LIMIT = 16;
    //api
    public function getDetailAuctions($auctionId)
    {
        $auctions = Auction::with('category', 'auctionStatus', 'items', 'comments', 'userSelling')
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

    //api
    public function getMaxPrice($auctionId) 
    {
        $price = Bid::where('auction_id', $auctionId)
            ->max('price');
        return $price;
    }

    //api
    public function getBids($auctionId) 
    {
        $bids = Bid::with('users')
            ->where('auction_id', $auctionId)
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return $bids;
    }

    //api
    public function getComments($auctionId) 
    {
        $comments = Comment::with('users')
            ->where('auction_id', $auctionId)
            ->orderBy('updated_at', 'desc')
            ->get();

        return $comments;
    }


    // value of category
    public function getCategoryValueName($auctionId)
    {
        $categoryId = Auction::findOrFail($auctionId)->category_id;

        $categoryValue = CategoryValue::where('category_id', $categoryId)
            ->get()
            ->pluck('name', 'category_value_id');

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
    //api
    public function getListAuctionByType($typeId)
    {
        $categoryId = Category::where('type', $typeId)
            ->get()
            ->pluck('category_id');

        $auction = Auction::with('category', 'auctionStatus')
            ->orderBy('created_at', 'DESC')
            ->whereIn('category_id', $categoryId)
            ->get();

        return $auction;
    }

    //get list auctions
    //api
    public function getListAuction()
    {
        $auction = Auction::with('category', 'auctionStatus')
            ->orderBy('created_at', 'DESC')
            ->get();

        return $auction;
    }

    //api
    public function getListAuctionsByUser($userId)
    {
        $list = Auction::with('category', 'auctionStatus')
            ->where('selling_user_id', $userId)
            ->get();

        return $list;
    }

    //validate create auction 
    //api
    public function auctionValidation($request)
    {
        $rules = [
            'category_id' => "required",
            'title_ni' => "required|max:255|unique:auctions,title",
            'start_date' => "required|date|after_or_equal:tomorrow",
            'end_date' => "required|date|after:start_date",
            'brand_id' => "required",
            'series' => "max:10|unique:items,series",
            'name' => "required|max:255",
            'starting_price' => 'required|numeric'
        ];

        $messages = [
            'required' => '必須項目が未入力です。',
            'max' => ':max文字以下入力してください。 ',
            'date' => 'データのフォーマットが正しくありません',
            'after_or_equal' => '始まる時間が明日か行かなければなりません',
            'after' => '始まる時間よりです。',
            'unique' => '既に使用されています。',
            'numeric' => '番号を入力してください。'
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

    //api
    public function create($request)
    {
        
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

        $item = Item::create([
            'category_id' => $auction->category_id,
            'selling_user_id' => $auction->selling_user_id,
            'auction_id' => $auction->auction_id,
            'brand_id' => $request['brand_id'],
            'series' => $request['series'] ?? null,
            'name' => $request['name'],
            'starting_price' => $request['starting_price'],
            'description' => $request['description'] ?? null
        ]);

        if (isset($request['images'])) {
            $images = $request['images'];
            foreach ($images as $key => $value) {
                if ($value != null) {
                    $image = Image::create([
                        'item_id' => $item->item_id,
                        'image' => $value
                    ]);
                }
            }
        }

        if (isset($request['values'])) {
            $values = $request['values'];
            foreach ($values as $key => $value)
            { 
                if ($value != null) {
                    $itemValues = ItemValue::create([
                        'item_id' => $item->item_id,
                        'category_value_id' => $key,
                        'value' => $value,
                    ]);
                }
            }
        }

        $categoryId = $auction->category_id;
        $itemValue = ItemValue::with(['categoryValues' => function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        }])
            ->where('item_id', $item->item_id)
            ->get();

        return $data = [
            'auctions' => [
                'auction_id' => $auction->auction_id,
                'title' => $auction->title,
                'category_id' => $auction->category_id,
                'selling_user_id' => $auction->selling_user_id,
                'start_date' => $auction->start_date,
                'end_date' => $auction->end_date,
                'created_at' => $auction->created_at,
                'updated_at' => $auction->updated_at
            ],
            'item' => [
                'item_id' => $item->item_id,
                'brand_id' => $item->brand_id,
                'series' => $item->series,
                'name' => $item->name,
                'description' => $item->description,
                'images' => $images ?? null,
                'values' => $itemValue->map(function ($value) {
                    return [
                        $value['categoryValues']['name'] => $value['value'],
                    ];
                }),
                'created_at' => $auction->created_at,
                'updated_at' => $auction->updated_at
            ],
        ];
    }

    //edit api
    public function edit($auctionId, $request)
    {
        //
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

    public function comments($auctionId, $request)
    {
        return Comment::create([
            'auction_id' => $auctionId, 
            'user_id' => auth()->user()->user_id,
            'content' => $request['content']
        ]);
    }
}
