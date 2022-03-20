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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuctionService implements AuctionServiceInterface
{
    const LIMIT = 16;
    //api
    public function getDetailAuctions($auctionId)
    {
        $auctions = Auction::with('category', 'items', 'comments', 'userSelling')
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

        $status1 = Auction::where('status', 1)
            ->count('auction_id');
        $status2 = Auction::where('status', 2)
            ->count('auction_id');
        $status4 = Auction::where('status', 4)
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

        $auction = Auction::with('category')
            ->orderBy('created_at', 'DESC')
            ->whereIn('category_id', $categoryId)
            ->get();

        return $auction;
    }

    //get list auctions
    //api
    public function getListAuction()
    {
        $auction = Auction::with('category')
            ->orderBy('created_at', 'DESC')
            ->get();

        return $auction;
    }

    //api
    public function getListAuctionsByUser($userId)
    {
        $list = Auction::with('category')
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
            'selling_user_id' => auth()->user()->user_id,
            'title' => $request['title_ni'],
            'start_date' => date('Y/m/d H:i', strtotime($request['start_date'])),
            'end_date' => date('Y/m/d H:i', strtotime($request['end_date'])),
            'status' => 4,
        ]);

        return $data = [
            'auctions' => [
                'auction_id' => $auction->auction_id,
                'title' => $auction->title,
                'category_id' => $auction->category_id,
                'selling_user_id' => $auction->selling_user_id,
                'start_date' => $auction->start_date,
                'end_date' => $auction->end_date,
                'status' => $auction->status,
                'reason' => $auction->reason,
                'created_at' => $auction->created_at,
                'updated_at' => $auction->updated_at
            ]
        ];
    }

    //edit api
    public function edit($auctionId, $request)
    {
        
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
        $status = Auction::findOrFail($auctionId)->status;

        if($status == 1 || $status == 2) {
            return Comment::create([
                'auction_id' => $auctionId, 
                'user_id' => auth()->user()->user_id,
                'content' => $request['content']
            ]);
        } else {
            return [
                'message' => 'Khong the comment',
            ];
        }
    }

    //bid validation
    public function bidValidation($request, $auctionId)
    {
        $maxBid = Bid::where('auction_id', $auctionId)
            ->max('price');

        $rules = [
            'price' => 'required|numeric'
        ];

        if ($request['price'] <= $maxBid) {
            $rules['price'] = 'max:0';
        }

        $messages = [
            'required' => '必須項目が未入力です。',
            'max' => '値段が今より高くなければなりません。',
            'number' => '番号を入力してください',
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
    }

    //create bids
    public function bids($auctionId, $request)
    {
        $status = Auction::findOrFail($auctionId)->status;

        if($status == 1 || $status == 2) {
            return Bid::create([
                'auction_id' => $auctionId, 
                'user_id' => auth()->user()->user_id,
                'price' => $request['price'],
                'phone' => $request['phone'] ?? null,
            ]);
        } else {
            return [
                'message' => 'Khong the nhap bid',
            ];
        }
    }

    public function likeValidation($request)
    {
        $rules = [
            'auction_id' => 'exists:auctions,auction_id',
            'item_id' => 'exists:items,item_id'
        ];

        $messages = [
            'exists' => 'Khong ton tai',
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
    }

    public function sellingInfo($auctionId)
    {
        $maxPrice = $this->getMaxPrice($auctionId);
    
        $userBuying = Bid::where('auction_id', $auctionId)->get()
            ->pluck('user_id')
            ->firstOrFail();

        $auction = Auction::findOrFail($auctionId);

        $item = Item::where('auction_id', $auctionId)
            ->get()
            ->firstOrFail();

        return [
            'buying_user_name' => User::findOrFail($userBuying)->name,
            'buying_user_phone' => User::findOrFail($userBuying)->phone,
            'buying_user_email' => User::findOrFail($userBuying)->email,
            'max_price' => $maxPrice,
            'auctions' => [
                'title' => $auction->title,
                'category_id' => $auction->category_id,
                'start_date' => $auction->start_date,
                'end_date' => $auction->end_date,
            ],
            'item' => [
                'name' => $item->name,
                'brand_id' => $item->brand_id,
                'series' => $item->series,
                'description' => $item->description,
                'selling_info' => $item->selling_info
            ],
        ];
    }

    //accept bid
    public function accept($request, $auctionId)
    {
        $check = Item::where('auction_id', $auctionId)
            ->whereNotNull('selling_info')
            ->get();

        if (isset($check[0])) {
            return $check;
        } else {
            $maxPrice = $this->getMaxPrice($auctionId);
    
            $userBuying = Bid::where('auction_id', $auctionId)->get()
                ->pluck('user_id')
                ->orderBy('price', 'DESC')
                ->firstOrFail();
    
            $auction = Auction::findOrFail($auctionId);
    
            $item = Item::where('auction_id', $auctionId)
                ->get()
                ->firstOrFail();

            if ($item) {
                $item->buying_user_id = $userBuying;
                $item->selling_info = $request['selling_info'];
                $item->update();
            }
    
            if ($auction) {
                $auction->status = 6;
                $auction->update();
            }
            
            $data = [
                'name' => $item->name,
                'buying_user_id' => $item->buying_user_id,
                'auction_id' => $item->auction_id,
                'selling_info' => $item->selling_info,
            ];
    
            return $data;
        }
    }
}
