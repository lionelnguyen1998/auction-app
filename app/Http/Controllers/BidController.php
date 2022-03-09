<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;
use App\Http\Services\BidService;

class BidController extends Controller
{
    protected $bidService;

    public function __construct(BidService $bidService)
    {
        $this->bidService = $bidService;
    }

    public function destroy($bidId)
    {
        $bid = Bid::where('bid_id', $bidId)->delete();
        return redirect()->back();
    }

    public function create(Request $request)
    {
        // $price = $request->price;
        // $phone = $request->phone;
        // $auctionId = $request->auction_id;
        // $userId = $request->user_id;
        // $itemId = $request->item_id;

        // $validated = $this->bidService->bidValidation($request->all());

        // if ($validated->fails()) {
        //     return redirect(url()->previous())
        //         ->withErrors($validated)
        //         ->withInput();
        // }

        // Bid::create([
        //     'price' => $price,
        //     'phone' => $phone,
        //     'auction_id' => $auctionId,
        //     'user_id' => $userId,
        //     'item_id' => $itemId
        // ]);

        $this->bidService->create($request);
        return redirect()->back();
    }
}
