<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionDeny extends Model
{
    use HasFactory, SoftDeletes; 
    protected $table = 'auctions_deny';
    protected $primaryKey = 'auction_deny_id';

    protected $fillable = [
        'auction_deny_id',
        'auction_id',
        'reason'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
