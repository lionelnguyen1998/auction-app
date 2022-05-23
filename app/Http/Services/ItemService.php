<?php

namespace App\Http\Services;

use App\Models\Item;
use App\Models\Image;
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
            'brand_id' => "required|exists:brands,brand_id",
            'series' => "max:10|unique:items,series",
            'name' => "required|max:255",
            'starting_price' => 'required|numeric',
            'description' => 'required'
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

    public function itemValidationEdit($request, $itemId) {
        $rules = [
            'brand_id' => "required",
            'series' => "max:10|unique:items,series,$itemId,item_id,deleted_at,NULL",
            'name' => "required|max:255",
            'starting_price' => 'required|numeric',
            'description' => 'required'
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
    
    //creat new item
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
            'description' => $request['description']
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

        $data = [
            'auction_id' => $item->auction_id,
            'brand_id' => $item->brand_id,
            'series' => $item->series,
            'name' => $item->name,
            'description' => $item->description,
            'images' => $images ?? null,
        ];
        return $data;
    }

    public function edit($request, $itemId, $images)
    {
        DB::beginTransaction();
            $item = Item::findOrFail($itemId);
            $imageId = Image::withTrashed()->where('item_id', $itemId)
                ->get()
                ->pluck('image_id');
            $image = Image::withTrashed()->findOrFail($imageId);
            $countImagesAdd = count($images);
            if ($images) {
                foreach ($image as $key => $value) {
                    if (isset($images[$key])) {
                        if ($value->deleted_at !== null) {
                            $value->image = $images[$key];
                            $value->restore();
                        } else {
                            $value->image = $images[$key];
                            $value->update();
                        }
                    } else {
                        break;
                    }
                }
                if (count($images) > count($image)) {
                    foreach ($images as $key => $value) {
                        if (($key + 1) > count($image)) {
                            Image::create([
                                'item_id' => $itemId,
                                'image' => $value
                            ]);
                        }
                    }
                }
            } else {
                Image::whereIn('image_id', $imageId)->delete();
            }
            unset($request['images']);
            $item = DB::table('items')->where('item_id', $itemId)->update($request);
        DB::commit();
    }
}
