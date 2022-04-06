<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Auction;
use App\Http\Services\AuctionService;

class HomeController extends Controller
{
    protected $auctionService;

    public function __construct(AuctionService $auctionService)
    {
        $this->auctionService = $auctionService;
    }

    public function welcome()
    {
        return view('welcome');
    }
    public function index() 
    {
        $slider = Slider::whereIn('type', [6, 7, 8])
            ->get();

        $auctionId = Auction::get()
            ->pluck('auction_id')
            ->toArray();

        $updateStatus = Auction::updateStatus($auctionId);

        return view('home', [
            'title' => 'オークション',
            'slider' => $slider,
            'logo' => Slider::logo(),
            'auctions' => $this->auctionService->getListAuction()
        ]);
    }

    public function loadAuction(Request $request) 
    {
        $page = $request->input('page', 0);
        $result = $this->auctionService->get($page);
        if (count($result) != 0) {
            $html = view('products.list', ['products' => $result ])->render();

            return response()->json([ 'html' => $html ]);
        }

        return response()->json(['html' => '' ]);
    }

}