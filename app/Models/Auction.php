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

    protected $fillable = [
        'auction_id',
        'category_id',
        'selling_user_id',
        'title',
        'title_en',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function auctionStatus()
    {
        return $this->hasOne(AuctionStatus::class, 'auction_id', 'auction_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'auction_id', 'auction_id');
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
        $auction = DB::table('auctions')
            ->join('auctions_status', 'auctions_status.auction_id', '=', 'auctions.auction_id')
            ->whereIn('auctions.auction_id', $auctionId)
            ->select('auctions.auction_id', 'auctions_status.status', 'auctions.start_date', 'auctions_status.auction_status_id', 'auctions.end_date')
            ->get()
            ->toArray();
        
        foreach ($auction as $key => $value) {
            $auctionStatus = AuctionStatus::findOrFail($value->auction_status_id);
            if ($auctionStatus && ($value->status != 4)) {
                if ($value->start_date <= now() && $value->end_date > now()) {
                    $auctionStatus->status = 1;
                    $auctionStatus->update();
                } elseif ($value->end_date <= now()) {
                    $auctionStatus->status = 3;
                    $auctionStatus->update();
                } else {
                    $auctionStatus->status = 2;
                    $auctionStatus->update();
                }
            }
        }
        
        return true;
    }

    public function auctionDeny()
    {
        return $this->hasOne(AuctionDeny::class, 'auction_id', 'auction_id');
    }
}
