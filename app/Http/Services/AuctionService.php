<?php

namespace App\Http\Services;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Image;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuctionService implements AuctionServiceInterface
{
    const LIMIT = 16;
    
    //detail auctions
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
    public function getListAuctionByType($typeId, $request)
    {
        $page = $request['page'];
        $perPage = $request['per_page'];
        $categoryId = Category::where('type', $typeId)
            ->get()
            ->pluck('category_id');

        $auction = Auction::with('category')
            ->orderBy('created_at', 'DESC')
            ->whereIn('category_id', $categoryId)
            ->forPage($page, $perPage)
            ->get();

        return $auction;
    }

    //get list auctions
    public function getListAuction($request)
    {
        $page = $request['page'];
        $perPage = $request['per_page'];
        $auction = Auction::with('category')
            ->where('status', '<>', 4)
            ->orderBy('created_at', 'DESC')
            ->forPage($page, $perPage)
            ->get();

        return $auction;
    }
    
    //get list auctions like
    public function getListAuctionLike($request)
    {
        $userId = auth()->user()->user_id;
        $auctionId = Favorite::where('user_id', $userId)
            ->where('is_liked', 1)
            ->get()
            ->pluck('auction_id');
        
        $page = $request['page'];
        $perPage = $request['per_page'];
        $auction = Auction::with('category')
            ->orderBy('created_at', 'DESC')
            ->whereIn('auction_id', $auctionId)
            ->forPage($page, $perPage)
            ->get();

        return $auction;
    }

    public function getListAuctionByStatus($statusId, $request)
    {
        $page = $request['page'];
        $perPage = $request['per_page'];
        $auction = Auction::with('category')
            ->where('status', $statusId)
            ->orderBy('created_at', 'DESC')
            ->forPage($page, $perPage)
            ->get();

        return $auction;
    }

    //get list auctions by user id
    public function getListAuctionsByUser($userId, $request)
    {
        $page = $request['page'];
        $perPage = $request['per_page'];
        $list = Auction::with('category')
            ->where('selling_user_id', $userId)
            ->forPage($page, $perPage)
            ->get();

        return $list;
    }

    //validate create auction 
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

    public function auctionValidationEdit($request, $auctionId)
    {
        $rules = [
            'category_id' => "required",
            'title_ni' => "required|max:255|unique:auctions,title,$auctionId,auction_id,deleted_at,NULL",
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
        $auction = Auction::findOrFail($auctionId);

        if ($auction) {
            $auction->category_id = $request['category_id'];
            $auction->start_date = $request['start_date'];
            $auction->end_date = $request['end_date'];
            $auction->title = $request['title_ni'];
            $auction->update();
        }
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
        $status = Auction::findOrFail($auctionId)->status;

        if($status == 1 || $status == 2) {
            $comment = Comment::create([
                'auction_id' => $auctionId, 
                'user_id' => auth()->user()->user_id,
                'content' => $request['content']
            ]);

            $data = [
                'auction_id' => $comment->auction_id,
                'user_id' => $comment->user_id,
                'content' => $comment->content,
                'update_date' => $comment->updated_at->format('Y/m/d H:i:s')
            ];

            return $data;
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

        if (isset($request['price']) && $request['price'] <= $maxBid) {
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
            $bid = Bid::create([
                'auction_id' => $auctionId, 
                'user_id' => auth()->user()->user_id,
                'price' => $request['price'],
                'phone' => $request['phone'] ?? null,
            ]);

            $data = [
                'auction_id' => $bid->auction_id,
                'user_id' => $bid->user_id,
                'price' => $bid->price,
                'phone' => $bid->phone,
                'updated_at' => $bid->updated_at->format('Y/m/d H:i:s')
            ];

            return $data;
        } else {
            return [
                'message' => 'Khong the nhap bid',
            ];
        }
    }

    public function likeValidation($request)
    {
        $rules = [
            'auction_id' => 'required|exists:auctions,auction_id',
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
        $item = Item::where('auction_id', $auctionId)
            ->get()
            ->firstOrFail();

        $auctionInfo = Auction::findOrFail($auctionId)
            ->where('auction_id', $auctionId)
            ->select('title', 'start_date', 'end_date')
            ->get();

        $itemInfo = Item::with('userBuying')
            ->where('auction_id', $auctionId)
            ->where('buying_user_id', auth()->user()->user_id)
            ->get()
            ->firstOrFail();
            
        $brand = Brand::where('brand_id', $item->brand_id)
            ->get()
            ->pluck('name')
            ->firstOrFail();

        return [
            'item_info' => [
                'name' => $itemInfo->name,
                'selling_user' => [
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'adress' => auth()->user()->address,
                    'phone' => auth()->user()->phone
                ],
                'buying_user' => [
                    'name' => $itemInfo->userBuying->name,
                    'email' => $itemInfo->userBuying->email,
                    'address' => $itemInfo->userBuying->email,
                    'phone' => $itemInfo->userBuying->phone
                ],
                'brand' => $brand,
                'series' => $itemInfo->series,
                'starting_price' => $itemInfo->starting_price,
                'max_price' => $maxPrice,
                'selling_info' => $itemInfo->selling_info,
            ],
            'auction_info' => $auctionInfo
        ];
    }

    //accept bid
    public function accept($request, $auctionId)
    {
        $check = Item::where('auction_id', $auctionId)
            ->whereNotNull('selling_info')
            ->get();

        $checkAuction = Auction::findOrFail($auctionId)
            ->where('auction_id', $auctionId)
            ->where('status', '<>', 3)
            ->get()
            ->toArray();

        $checkSellingUser = Auction::findOrFail($auctionId)
            ->where('selling_user_id', auth()->user()->user_id)
            ->get()
            ->toArray();

        $bids = Bid::where('auction_id', $auctionId)
            ->get()
            ->toArray();
        
        if (empty($checkSellingUser))
        {
            return [
                'message' => 'User không có quyền'
            ];
        }
        if ($checkAuction)
        {
            return [
                'message' => 'Phiên đấu giá chưa kết thúc hoặc phiên đấu giá không tồn tại'
            ];
        }
        if (isset($check[0])) {
            return [ 
                'message' => 'Đã chấp nhận'
            ];
        }
        if (empty($bids)) {
            return [ 
                'message' => 'Chưa có trả giá nào'
            ];
        } else {
            $maxPrice = $this->getMaxPrice($auctionId);
    
            $userBuying = Bid::where('auction_id', $auctionId)
                ->orderBy('price', 'DESC')
                ->get()
                ->pluck('user_id')
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

            $auctionInfo = Auction::findOrFail($auctionId)
                ->where('auction_id', $auctionId)
                ->select('title', 'start_date', 'end_date')
                ->get();

            $itemInfo = Item::with('userBuying')
                ->where('auction_id', $auctionId)
                ->where('buying_user_id', $item->buying_user_id)
                ->get()
                ->firstOrFail();
            $brand = Brand::where('brand_id', $item->brand_id)
                ->get()
                ->pluck('name')
                ->firstOrFail();
               
            $data = [
                'item_info' => [
                    'name' => $itemInfo->name,
                    'selling_user' => [
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'adress' => auth()->user()->address,
                        'phone' => auth()->user()->phone
                    ],
                    'buying_user' => [
                        'name' => $itemInfo->userBuying->name,
                        'email' => $itemInfo->userBuying->email,
                        'address' => $itemInfo->userBuying->email,
                        'phone' => $itemInfo->userBuying->phone
                    ],
                    'brand' => $brand,
                    'series' => $itemInfo->series,
                    'starting_price' => $itemInfo->starting_price,
                    'max_price' => $maxPrice,
                    'selling_info' => $itemInfo->selling_info,
                ],
                'auction_info' => $auctionInfo
            ];
    
            return $data;
        }
    }

    //get auction by categoryId and typeCategory
    public function getAuctionByCategory($typeId)
    {
        $categoryId = Category::where('type', $typeId)
            ->get()
            ->pluck('category_id')
            ->toArray();

        $auction = Auction::with('category', 'items')
            ->whereIn('category_id', $categoryId)
            ->get()
            ->toArray();

        return $auction;
    }

    public function getInfor($auctionId)
    {
        $categoryId = Auction::findOrFail($auctionId)->category_id;

        $itemId = Item::where('auction_id', $auctionId)
            ->where('category_id', $categoryId)
            ->get()
            ->pluck('item_id');

        return $itemInfor;
    }
}
