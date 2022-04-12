<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\AuctionStatus;

class Auction extends Model
{
    use HasFactory;
    use SoftDeletes; 
    protected $table = 'auctions';
    protected $primaryKey = 'auction_id';

    const PER_PAGE = 20;

    protected $fillable = [
        'auction_id',
        'category_id',
        'selling_user_id',
        'title',
        'start_date',
        'end_date',
        'status',
        'reason',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function items()
    {
        return $this->hasOne(Item::class, 'auction_id', 'auction_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'auction_id', 'auction_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'auction_id', 'auction_id');
    }

    public function updateStatus($auctionId)
    {
        $auctions = Auction::findOrFail($auctionId);
        
        foreach ($auctions as $key => $value) {
            $auction = Auction::findOrFail($value->auction_id);
            if ($auction && ($value->status != 4) && ($value->status != 6)) {
                if ($value->start_date <= now() && $value->end_date > now()) {
                    $auction->status = 1;
                    $auction->update();
                } elseif ($value->end_date <= now()) {
                    $auction->status = 3;
                    $auction->update();
                } else {
                    $auction->status = 2;
                    $auction->update();
                }
            }
        }
        
        return true;
    }
    
    public function userSelling()
    {
        return $this->belongsTo(User::class, 'selling_user_id', 'user_id');
    }

    public function auctionSelling()
    {
        return $this->hasOne(AuctionSelling::class, 'auction_id', 'auction_id');
    }

    public function listDeny()
    {
        $userId = auth()->user()->user_id;
        
        $auctionDeny = Auction::withTrashed()
            ->where('selling_user_id', $userId)
            ->where('status', '=', 5)
            ->get();

        return $auctionDeny;
    }
}
