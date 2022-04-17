<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReadNews extends Model
{
    use HasFactory;

    protected $table = 'user_read_news';
    protected $primaryKey = 'user_read_new_id';

    protected $fillable = [
        'user_read_new_id',
        'new_id',
        'auction_id',
        'is_read'
    ];
}
