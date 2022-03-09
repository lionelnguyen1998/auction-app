<?php

namespace App\Http\Services;

use App\Models\Item;
use App\Models\Image;
use Illuminate\Support\Facades\Validator;

class ItemAdminService implements ItemAdminServiceInterface
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
            'series' => "max:10",
            'name' => "required|max:255",
            'name_en' => "max:255",
            'starting_price' => 'required|integer|max:4294967295'
        ];

        $messages = [
            'required' => '必須項目が未入力です。',
            'max' => ':max文字以下入力してください。 ',
            'starting_price.max' => 'SO qua lon',
            'integer' => 'hay nhap so'
        ];

        $attributes = [
            
        ];

        $validated = Validator::make($request, $rules, $messages, $attributes);

        return $validated;
    }
}
