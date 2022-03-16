<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\ItemService;
use App\Http\Services\AuctionService;
use App\Models\Item;
use App\Models\ItemValue;
use App\Models\Image;
use App\Models\Brand;
use App\Models\Slider;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $itemService, $auctionService;
    public function __construct(ItemService $itemService, AuctionService $auctionService)
    {
        $this->itemService = $itemService;
        $this->auctionService = $auctionService;
    }

    //create Item
    public function create($auctionId, $categoryId)
    {
        return view('items.create', [
            'title' => 'アイテム追加',
            'auctionId' => $auctionId,
            'categoryId' => $categoryId,
            'categoryValueName' => $this->auctionService->getCategoryValueName($auctionId),
            'brand' => Brand::all(),
            'logo' => Slider::logo(),
        ]);
    }

    //insert item
    public function store(Request $request)
    {
        $validated = $this->itemService->itemValidation($request->all());

        if ($validated->fails()) {
            return redirect(url()->previous())
                ->withErrors($validated)
                ->withInput();
        }

        $this->itemService->registerItem($request->all());

        return redirect()->route('listAuctions', ['userId' => auth()->user()->user_id])->with('message', 'オークションを追加しました。');
    }
}
