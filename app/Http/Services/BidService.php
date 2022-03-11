<?php

namespace App\Http\Services;

use App\Models\Bid;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;

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
    try {
        DB::beginTransaction();

        $bids = Session::get('bids');

        $bidCreate = Bid::create([
                'price' => $request->price,
                'phone' => $request->phone,
                'auction_id' => $auctionId,
                'user_id' => $request->auction_id,
                'item_id' => $request->item_id
            ]);

        DB::commit();
        Session::flash('success', 'Đặt Hàng Thành Công');

        Session::forget('bids');
    } catch (\Exception $err) {
        DB::rollBack();
        Session::flash('error', 'Đặt Hàng Lỗi, Vui lòng thử lại sau');
        return false;
    }

    return true;
   }
}