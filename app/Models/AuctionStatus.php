<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionStatus extends Model
{
    use HasFactory;

    protected $table = 'auctions_status';
    protected $primaryKey = 'auction_status_id';

    protected $fillable = [
        'auction_status_id',
        'auction_id',
        'status'
    ];
}
