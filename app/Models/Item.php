<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'item_id',
        'category_id',
        'auction_id',
        'selling_user_id',
        'buying_user_id',
        'brand_id',
        'series',
        'name',
        'starting_price',
        'selling_info',
        'description'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'selling_user_id', 'user_id');
    }

    public function userBuying()
    {
        return $this->belongsTo(User::class, 'buying_user_id', 'user_id');
    }

    public function auctions()
    {
        return $this->belongsTo(Auction::class, 'auction_id', 'auction_id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function brands()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'item_id', 'item_id');
    }

    public function listAccept()
    {
        $userId = auth()->user()->user_id;
       
        $auctionId = Auction::where('status', '=', 6)
            ->get()
            ->pluck('auction_id');
   
        if (isset($auctionId[0])) {
            $auctionSelling = Auction::join('items', 'items.auction_id', '=', 'auctions.auction_id')
                ->where('items.buying_user_id', $userId)
                ->where('auctions.auction_id', $auctionId)
                ->get();
    
            return $auctionSelling;
        } else {
            return false;
        }   
    }

}
