<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Auction;

class HomeController extends Controller
{
    public function index() 
    {
        $slider = Slider::whereIn('type', [1, 2, 3])
            ->get();

        $auctionId = Auction::get()
        ->pluck('auction_id')
        ->toArray();

        $updateStatus = Auction::updateStatus($auctionId);
    
        return view('home', [
            'title' => 'オークション',
            'slider' => $slider,
        ]);
    }
}
