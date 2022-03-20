<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table = 'favories';
    protected $primaryKey = 'favorite_id';

    protected $fillable = [
        'favorite_id', 
        'user_id',
        'auction_id',
        'item_id',
        'is_liked',
    ];
}
