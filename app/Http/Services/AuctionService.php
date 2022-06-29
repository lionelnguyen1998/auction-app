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
use App\Models\Rate;
use App\Models\Favorite;
use App\Http\Controllers\Api\AuctionController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuctionService implements AuctionServiceInterface
{
    const LIMIT = 16;
    
    //detail auctions
    public function getDetailAuctions($auctionId)
    {
        $auctions = Auction::with('category', 'items', 'comments', 'userSelling', 'like')
            ->where('auction_id', $auctionId)
            ->get()
            ->toArray();
        return $auctions;
    }

    public function getDetailAuctions1($auctionId)
    {
        $auctions = Auction::with('category', 'items')
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
    public function getListAuctionByType($typeId, $statusId, $request)
    {
        $page = $request['index'];
        $perPage = $request['count'];
        $categoryId = Category::where('type', $typeId)
            ->get()
            ->pluck('category_id');

        if ($statusId == 0) {
            $auction = Auction::with('category')
                ->whereIn('auctions.status', [1, 2, 3])
                ->orderBy('created_at', 'DESC')
                ->whereIn('category_id', $categoryId)
                ->forPage($page, $perPage)
                ->get();
        } else {
            $auction = Auction::with('category')
                ->where('auctions.status', $statusId)
                ->orderBy('created_at', 'DESC')
                ->whereIn('category_id', $categoryId)
                ->forPage($page, $perPage)
                ->get();
        }

        return $auction;
    }

    //get list auctions
    public function getListAuction($request)
    {
        $page = $request['index'];
        $perPage = $request['count'];
        $auction = Auction::with('category')
            ->where('status', '<>', 4)
            ->orderBy('created_at', 'DESC')
            ->forPage($page, $perPage)
            ->get();

        return $auction;
    }
    
    //get list auctions like
    public function getListAuctionLike($statusId, $request)
    {
        $userId = auth()->user()->user_id;
        $auctionId = Favorite::where('user_id', $userId)
            ->where('is_liked', 1)
            ->get()
            ->pluck('auction_id');
        
        $page = $request['index'];
        $perPage = $request['count'];
        if ($statusId == 0) {
            $auction = Auction::with('category')
                ->orderBy('created_at', 'DESC')
                ->whereIn('auction_id', $auctionId)
                ->forPage($page, $perPage)
                ->get();
        } else {
            $auction = Auction::with('category')
                ->orderBy('created_at', 'DESC')
                ->whereIn('auction_id', $auctionId)
                ->where('status', $statusId)
                ->forPage($page, $perPage)
                ->get();
        }

        return $auction;
    }

    public function getListAuctionByStatus($statusId, $request)
    {
        $page = $request['index'];
        $perPage = $request['count'];
        if ($statusId == 0) {
            $auction = Auction::with('category')
                ->whereIn('status', [1, 2, 3, 6, 7, 8])
                ->orderBy('created_at', 'DESC')
                ->forPage($page, $perPage)
                ->get();
        } else if ($statusId == 6) {
            $auction = Auction::with('category')
                ->whereIn('status', [6, 7, 8])
                ->orderBy('created_at', 'DESC')
                ->forPage($page, $perPage)
                ->get();
        } else {
            $auction = Auction::with('category')
                ->where('status', $statusId)
                ->orderBy('created_at', 'DESC')
                ->forPage($page, $perPage)
                ->get();
        }

        return $auction;
    }

    //get list auctions by user id
    public function getListAuctionsByUser($userId, $statusId, $request)
    {
        $page = $request['index'];
        $perPage = $request['count'];
        if ($statusId == 0) {
            $list = Auction::with('category')
                ->where('selling_user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->forPage($page, $perPage)
                ->get();
        } else if ($statusId == 6) {
            $list = Auction::with('category')
                ->whereIn('status', [6,7,8])
                ->where('selling_user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->forPage($page, $perPage)
                ->get();
        } else {
            $list = Auction::with('category')
                ->where('status', $statusId)
                ->where('selling_user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->forPage($page, $perPage)
                ->get();
        }

        return $list;
    }

     //get list auctions by user id
     public function getListAuctionsByUserK($userId, $statusId, $request)
     {
         $page = $request['index'];
         $perPage = $request['count'];
         if ($statusId == 0) {
             $list = Auction::with('category')
                 ->where('selling_user_id', $userId)
                 ->orderBy('created_at', 'DESC')
                 ->where('status', '<>', 4)
                 ->forPage($page, $perPage)
                 ->get();
         } else if ($statusId == 6) {
            $list = Auction::with('category')
                ->where('selling_user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->whereIn('status', [6,7,8])
                ->forPage($page, $perPage)
                ->get();
         } else {
             $list = Auction::with('category')
                 ->where('status', $statusId)
                 ->where('selling_user_id', $userId)
                 ->orderBy('created_at', 'DESC')
                 ->where('status', '<>', 4)
                 ->forPage($page, $perPage)
                 ->get();
         }
 
         return $list;
     }

    //list auctions by category
    public function getListAuctionOfCategory($request, $categoryId, $statusId)
    {
        $page = $request['index'];
        $perPage = $request['count'];

        if ($statusId == 0) {
            $list = Auction::with('category')
                ->whereIn('auctions.status', [1, 2, 3])
                ->where('category_id', $categoryId)
                ->orderBy('created_at', 'DESC')
                ->forPage($page, $perPage)
                ->get();
        } else {
            $list = Auction::with('category')
                ->where('status', $statusId)
                ->where('category_id', $categoryId)
                ->orderBy('created_at', 'DESC')
                ->forPage($page, $perPage)
                ->get();
        }

        return $list;
    }
    //validate create auction 
    public function auctionValidation($request)
    {
        $rules = [
            'category_id' => "required|exists:categories,category_id",
            'start_date' => "required|date|after_or_equal:tomorrow",
            'end_date' => "required|date|after:start_date",
        ];

        $allAuctions = Auction::get()
            ->pluck('title')
            ->toArray();

        if (isset($request['title_ni'])) {
            foreach ($allAuctions as $key => $value) {
                if ($request['title_ni'] == $value) {
                    $rules['title_ni'] = "required|max:255|unique:auctions,title";
                    break;
                } else {
                    $rules['title_ni'] = "required|max:255";
                }
            }
        } else {
            $rules['title_ni'] = "required|max:255";
        }

        // $messages = [
        //     'required' => '必須項目が未入力です。',
        //     'max' => ':max文字以下入力してください。 ',
        //     'date' => 'データのフォーマットが正しくありません',
        //     'after_or_equal' => '始まる時間が明日か行かなければなりません',
        //     'after' => '始まる時間よりです。',
        //     'unique' => '既に使用されています。',
        //     'numeric' => '番号を入力してください。',
        //     'exists' => '存在しない。'
        // ];

        $messages = [
            'required' => 7000,
            'max' => 7001,
            'date' => 7008,
            'after_or_equal' => 7009,
            'after' => 7010,
            'unique' => 7005,
            'numeric' => 7006,
            'exists' => 7007
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

        // $messages = [
        //     'required' => '必須項目が未入力です。',
        //     'max' => ':max文字以下入力してください。 ',
        //     'date' => 'データのフォーマットが正しくありません',
        //     'after_or_equal' => '始まる時間が明日か行かなければなりません',
        //     'after' => '始まる時間よりです。',
        //     'unique' => '既に使用されています。',
        //     'numeric' => '番号を入力してください。'
        // ];

        $messages = [
            'required' => 7000,
            'max' => 7001,
            'date' => 7008,
            'after_or_equal' => 7009,
            'after' => 7010,
            'unique' => 7005,
            'numeric' => 7006,
            'exists' => 7007
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

    //create auctions
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
            'auction_id' => $auction->auction_id,
            'title' => $auction->title,
            'category_id' => $auction->category_id,
            'selling_user_id' => $auction->selling_user_id,
            'start_date' => $auction->start_date,
            'end_date' => $auction->end_date,
            'status' => $auction->status,
            'reason' => $auction->reason,
        ];
    }

    //edit auctions
    public function edit($auctionId, $request)
    {
        $auction = Auction::findOrFail($auctionId);

        if ($auction) {
            $auction->category_id = $request['category_id'];
            $auction->start_date = date('Y/m/d H:i', strtotime($request['start_date']));
            $auction->end_date = date('Y/m/d H:i', strtotime($request['end_date']));
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

    public function commentValidation($request) {
        $rules = [
            'content' => "required",
        ];

        $messages = [
            'required' => '必須項目が未入力です。',
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
    }

    public function comments($auctionId, $request)
    {
        $status = Auction::findOrFail($auctionId)->status;
        $lastIdComment = Comment::orderBy('updated_at', 'DESC')
            ->get()
            ->pluck('comment_id')
            ->first();

        $lastId = $request['comment_last_id'] ?? $lastIdComment;

        if($status != 4) {
            $comment = Comment::create([
                'auction_id' => $auctionId, 
                'user_id' => auth()->user()->user_id,
                'content' => $request['content']
            ]);

            $total = Comment::where('auction_id', $auctionId)
                ->count('comment_id');

            $currentId = $comment->comment_id;

            if ($currentId == ($lastId + 1)) {
                $data = [
                    'auction_id' => $comment->auction_id,
                    'user_id' => $comment->user_id,
                    'content' => $comment->content,
                    'update_date' => $comment->updated_at->format('Y/m/d H:i:s'),
                    'total' => $total
                ];
            } else {

                $comments = Comment::where('auction_id', $auctionId)
                    ->where('comment_id', '>', $lastId)
                    ->orderBy('created_at', 'DESC')
                    ->get();

                $data = [
                    'comments' => $comments->map(function($comment) {
                        return [
                            'auction_id' => $comment->auction_id,
                            'user_id' => $comment->user_id,
                            'content' => $comment->content,
                            'updated_at' => $comment->updated_at->format('Y/m/d H:i:s'),
                            
                        ];
                    }),
                    'total' => $total
                ];
            }

            return $data;
        } else {
            return [
                "code" => 1008,
                "message" => "Không thể bình luận",
                "data" => null,
            ];
        }
    }

    //bid validation
    public function bidValidation($request, $auctionId, $startPrice)
    {
        $maxBid = Bid::where('auction_id', $auctionId)
            ->max('price');

        $rules = [
            'price' => 'required|numeric'
        ];

        if (isset($request['price']) && ($request['price'] <= ($maxBid + 5) || $request['price'] < $startPrice)) {
            $rules['price'] = 'max:0';
        }

        // $messages = [
        //     'required' => '必須項目が未入力です。',
        //     'max' => '値段が今より高くなければなりません。',
        //     'numeric' => '番号を入力してください',
        // ];

        $messages = [
            'required' => 7000,
            'price.max' => 7014,
            'numeric' => 7006,
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
    }

    //create bids
    public function bids($auctionId, $request)
    {
        $status = Auction::findOrFail($auctionId)->status;

        $lastIdBid = Bid::orderBy('updated_at', 'DESC')
        ->get()
        ->pluck('bid_id')
        ->first();

        $lastId = $request['bid_last_id'] ?? $lastIdBid;

        if($status == 1 || $status == 2) {
            $bid = Bid::create([
                'auction_id' => $auctionId, 
                'user_id' => auth()->user()->user_id,
                'price' => $request['price'],
                'phone' => $request['phone'] ?? null,
            ]);

            $total = Bid::where('auction_id', $auctionId)
                ->count('bid_id');

            $currentId = $bid->bid_id;

            if ($currentId == ($lastId + 1)) {
                $data = [
                    'auction_id' => $bid->auction_id,
                    'user_id' => $bid->user_id,
                    'price' => $bid->price,
                    'phone' => $bid->phone,
                    'updated_at' => $bid->updated_at->format('Y/m/d H:i:s'),
                    'total' => $total
                ];
            } else {
                $bids = Bid::where('auction_id', $auctionId)
                    ->where('bid_id', '>', $lastId)
                    ->orderBy('price', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->get();

                $data = [
                    'bids' => $bids->map(function($bid) {
                        return [
                            'auction_id' => $bid->auction_id,
                            'user_id' => $bid->user_id,
                            'price' => $bid->price,
                            'phone' => $bid->phone,
                            'updated_at' => $bid->updated_at->format('Y/m/d H:i:s'),
                            'total' => $total
                        ];
                    }),
                ];
            }

            return $data;
        } else {
            return [
                'message' => 'Không thể nhập bid',
            ];
        }
    }

    public function likeValidation($request)
    {
        $rules = [
            'auction_id' => 'required|exists:auctions,auction_id',
        ];

        $messages = [
            'exists' => 'Phiên đấu giá không tồn tại',
            'required' => '必須項目が未入力です。'
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
            ->select('title', 'start_date', 'end_date', 'auction_id')
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

    public function sellingValidation($request)
    {
        $rules = [
            'selling_info' => 'required',
        ];

        $messages = [
            'required' => '必須項目が未入力です。'
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
    }

    //accept bid
    public function accept($request, $auctionId)
    {
        $status = Auction::findOrFail($auctionId)->status;

        $checkAuction = Auction::findOrFail($auctionId)
            ->where('auction_id', $auctionId)
            ->where('status', '<>', 3)
            ->get()
            ->toArray();

        $bids = Bid::where('auction_id', $auctionId)
            ->get()
            ->toArray();
        
        if ($status == 6) {
            return [
                "code" => 1010,
                "message" => "Đã bán",
                "data" => null,
            ];
        }
        if ($checkAuction)
        {
            return [
                "code" => 1009,
                "message" => "Phiên đấu giá chưa kết thúc",
                "data" => null,
            ];
        }
        
        if (empty($bids)) {
            return [
                "code" => 1011,
                "message" => "Chưa có trả giá nào",
                "data" => null,
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
    
            return [
                "code" => 1000,
                "message" => "Ok",
                "data" => $data,
            ];
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

    public function updateDelivery($auctionId) {
        $auction = Auction::findOrFail($auctionId);
     
        $sellingUserId = $auction->selling_user_id;
        $buyingUserId = Item::where('auction_id', $auctionId)
            ->get()
            ->pluck('buying_user_id')
            ->first();

        $userId = auth()->user()->user_id;
       
        $status = $auction->status;
        if (($status === 6) && ($sellingUserId === $userId)) {
            $auction->status = 7;
            $auction->update();
            return [
                "code" => 1000,
                "message" => "Đã giao",
                "data" => null,
            ];
        }
        if (($status === 6) && ($sellingUserId !== $userId)) {
            return [
                "code" => 1006,
                "message" => "Bạn không có quyền",
                "data" => null,
            ];
        }
        
        if (($status === 7) && ($buyingUserId === $userId)) {
            $auction->status = 8;
            $auction->update();
            return [
                "code" => 1000,
                "message" => "Đã giao thành công",
                "data" => null,
            ];
        } 
        if (($status === 7) && ($buyingUserId !== $userId)) {
            return [
                "code" => 1006,
                "message" => "Bạn không có quyền",
                "data" => null,
            ];
        }
    }

    public function rateValidation($request) {
        $rules = [
            'star' => "required",
            'content' => "required"
        ];

        $messages = [
            'required' => 7000,
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
    }

    public function rate($request, $auctionId) {
        $rateId = Rate::where('auction_id', $auctionId)
            ->get()
            ->pluck('rate_id')
            ->first();

        if ($rateId) {
            $rate = Rate::findOrFail($rateId);
        } else {
            $this->updateDelivery($auctionId);
            $rate = Rate::create([
                'star' => $request['star'],
                'content' => $request['content'],
                'auction_id' => $auctionId,
                'buying_user_id' => auth()->user()->user_id,
                'image' => $request['image'] ?? null
            ]);
        }

        $data = [
            'star' => $rate->star,
            'content' => $rate->content,
            'auction_id' => $rate->auction_id,
            'buying_user_id' => $rate->buying_user_id,
            'image' => $rate->image ?? null
        ];

        return $data;
    }

    public function rateEdit($request, $rateId) {
        $rate = Rate::findOrFail($rateId);
        if ($rate) {
            $rate->star = $request['star'];
            $rate->content = $request['content'];
            $rate->image = $request['image'] ?? null;
            $rate->update();
        }

        $update = Rate::findOrFail($rateId);

        $data = [
            'star' => $update->star,
            'content' => $update->content,
            'auctin_id' => $update->auction_id,
            'buying_user_id' => $update->buying_user_id,
            'image' => $update->image ?? null
        ];

        return $data;
    }
}
