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

    public function create(Request $request)
    {
        $this->bidService->create($request->all());
        return redirect()->back();
    }
}
