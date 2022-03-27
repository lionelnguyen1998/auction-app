<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function like($auctionId, Request $request)
    {
        $id = Favorite::where('auction_id', '=', $auctionId)
            ->get()
            ->pluck('favorite_id')
            ->toArray();

        if ($id) {           
            $like = Favorite::findOrFail($id[0])->is_liked;
            $favorite = Favorite::findOrFail($id[0]);
            $favorite->is_liked = $like ? 0 : 1;
            $favorite->save();
        } else {
            Favorite::insert([
                'auction_id' => $auctionId ?? null,
                'is_liked' => 1,
                'user_id' => auth()->user()->user_id
            ]);
        }

        return redirect()->back();
    }
}
