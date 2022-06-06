<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $table = 'rate';
    protected $primaryKey = 'rate_id';

    protected $fillable = [
       'rate_id',
       'buying_user_id',
       'auction_id',
       'star',
       'content',
       'image'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'buying_user_id', 'user_id');
    }
}
