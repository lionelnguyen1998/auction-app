<?php

namespace App\Http\Services;

use App\Models\Item;
use App\Models\Image;
use App\Models\ItemValue;
use App\Models\Auction;
use App\Models\AuctionStatus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ItemService implements ItemServiceInterface
{
    public function getCountItems($categoryId) 
    {
        $countItems = Item::where('category_id', $categoryId)
            ->count('item_id');
        return $countItems;
    }

    public function getListItems($categoryId) 
    {
        $listItems = Item::with('series', 'brands')
            ->where('category_id', $categoryId)
            ->get()
            ->toArray();

        return $listItems;
    }

    public function getAllItems()
    {
        return Item::with('brands', 'categories')
            ->get()
            ->toArray();
    }

    public function getItem($itemId)
    {
        $item = Item::with('users', 'categories', 'auctions', 'brands')
            ->where('item_id', $itemId)
            ->get()
            ->toArray();
        
        return $item;
    }

    //get images items
    public function getImageLists($itemId)
    {
        $image = Image::where('item_id', $itemId)
            ->get()
            ->pluck('image');
        
        return $image;
    }

    //get list Item of auction 
    public function getListItemOfAuction($auctionId)
    {
        $itemList = Item::with('images')
            ->where('auction_id', $auctionId)
            ->get()
            ->toArray();
        return $itemList;
    }

    //validate item
    //api
    public function itemValidation($request)
    {
        $rules = [
            'brand_id' => "required",
            'series' => "max:10|unique:items,series",
            'name' => "required|max:255",
            'name_en' => "max:255",
            'starting_price' => 'required|numeric'
        ];

        $messages = [
            'required' => '必須項目が未入力です。',
            'max' => ':max文字以下入力してください。 ',
            'unique' => '既に使用されています。',
            'numeric' => '番号を入力してください。'
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
    }

    public function registerItem($request)
    {
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

        $images = $request['images'];
        foreach ($images as $key => $value) {
            if ($value != null) {
                Image::create([
                    'item_id' => $item->item_id,
                    'image' => $value
                ]);
            }
        }
        $values = $request['values'];
        foreach ($values as $key => $value)
        { 
            if ($value != null) {
                $itemValues = ItemValue::create([
                    'item_id' => $item->item_id,
                    'category_value_id' => $key,
                    'value' => $value,
                ]);
            }
        }
    }

    //api
    public function getInfor($itemId)
    {
        $categoryId = Item::findOrFail($itemId[0])->category_id;
        $itemInfor = ItemValue::with(['categoryValues' => function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        }])
            ->where('item_id', $itemId)
            ->get();

        return $itemInfor;
    }

    //api
    public function create($request, $auctionId, $images)
    {
        $auction = Auction::findOrFail($auctionId);

        $item = Item::create([
            'category_id' => $auction->category_id,
            'selling_user_id' => $auction->selling_user_id,
            'auction_id' => $auction->auction_id,
            'brand_id' => $request['brand_id'],
            'series' => $request['series'] ?? null,
            'name' => $request['name'],
            'starting_price' => $request['starting_price'],
            'description' => $request['description'] ?? null
        ]);

        if (isset($images)) {
            foreach ($images as $key => $value) {
                if ($value != null) {
                    $image = Image::create([
                        'item_id' => $item->item_id,
                        'image' => $value
                    ]);
                }
            }
        }

        if (isset($request['values'])) {
            $values = $request['values'];
            foreach ($values as $key => $value)
            { 
                if ($value != null) {
                    $itemValues = ItemValue::create([
                        'item_id' => $item->item_id,
                        'category_value_id' => $key,
                        'value' => $value,
                    ]);
                }
            }
        }

        $categoryId = $auction->category_id;
        $itemValue = ItemValue::with(['categoryValues' => function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        }])
            ->where('item_id', $item->item_id)
            ->get();
        $data = [
            'item' => [
                'item_id' => $item->item_id,
                'brand_id' => $item->brand_id,
                'series' => $item->series,
                'name' => $item->name,
                'description' => $item->description,
                'images' => $images ?? null,
                'values' => $itemValue->map(function ($value) {
                    return [
                        $value['categoryValues']['name'] => $value['value'],
                    ];
                }),
                'created_at' => $auction->created_at,
                'updated_at' => $auction->updated_at,
            ],
        ];
        return $data;
    }

    public function edit($request, $itemId)
    {
        DB::beginTransaction();
            $item = Item::findOrFail($itemId);
            $auctionId = $item->auction_id;
            $status = AuctionStatus::where('auction_id', '=', $auctionId)
                ->get()
                ->pluck('status')
                ->firstOrFail();
            
            if ($status == 4) {
                // if (isset($request['images'])) {
                //     $images = $request['images'];
                //     $image = DB::table('images')->where('item_id', $itemId)->update($images);
                // }
        
                // if (isset($request['values'])) {
                //     $values = $request['values'];
                //     $values = DB::table('item_values')->where('item_id', $itemId)->update($values);
                // }
                unset($request['values']);
                unset($request['images']);
                $item = DB::table('items')->where('item_id', $itemId)->update($request);
            } else {
                return [
                    'message' => 'Khong the chinh sua'
                ];
            }
        DB::commit();
    }
}
