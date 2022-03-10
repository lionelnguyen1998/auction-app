<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\ItemService;
use App\Http\Services\AuctionService;
use App\Models\Item;
use App\Models\ItemValue;
use App\Models\Image;
use App\Models\Brand;
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
            'title' => 'Tao item',
            'auctionId' => $auctionId,
            'categoryId' => $categoryId,
            'categoryValueName' => $this->auctionService->getCategoryValueName($auctionId),
            'brand' => Brand::all()
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
        //dd($request->all());
        $item = Item::create([
            'category_id' => $request['category_id'],
            'selling_user_id' => $request['selling_user_id'],
            'auction_id' => $request['auction_id'],
            'brand_id' => $request['brand_id'],
            'series' => $request['series'],
            'name' => $request['name'],
            'name_en' => $request['name_en'],
            'starting_price' => $request['starting_price'],
            'description' => $request['description']
        ]);

        Image::create([
            'item_id' => $item->item_id,
            'image' => $request['thumbuser'],
        ]);

        $itemRequest = $request->except('selling_user_id', 
            'auction_id', 'category_id', 'brand_id', 'series', 'name', 'name_en', 
            'starting_price', 'description', 'thumbuser', '_token');

        foreach ($itemRequest as $key => $value)
        { 
            if ($value != null) {
                $itemValues = ItemValue::create([
                    'item_id' => $item->item_id,
                    'category_value_id' => $key,
                    'value' => $value,
                ]);
            }
        }
        dd('abc');
    }
}
