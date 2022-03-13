<?php

namespace App\Http\Services;

use App\Models\Bid;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BidService implements BidServiceInterface
{
   public function bidValidation($request)
   {
        $maxBid = Bid::where('auction_id', $request['auction_id'])
            ->max('price');

        $rules = [
            'price' => 'numeric'
        ];

        if ($request['price'] <= $maxBid) {
            $rules['price'] = 'max:0';
        }

        $messages = [
            'max' => '値段が今より高くなければなりません。',
            'number' => '番号を入力してください',
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
   }

   public function create($request)
   {
        $price = $request['price'];
        $phone = $request['phone'];
        $auctionId = $request['auction_id'];
        $userId = $request['user_id'];
        $itemId = $request['item_id'];

        $validated = $this->bidValidation($request);

        if ($validated->fails()) {
            return redirect(url()->previous())
                ->withErrors($validated)
                ->withInput();
        }

        return Bid::create([
            'price' => $price,
            'phone' => $phone,
            'auction_id' => $auctionId,
            'user_id' => $userId,
            'item_id' => $itemId
        ]);
    
   }
}