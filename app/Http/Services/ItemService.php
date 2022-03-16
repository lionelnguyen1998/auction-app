<?php

namespace App\Http\Services;

use App\Models\Item;
use App\Models\Image;
use App\Models\ItemValue;
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
}
