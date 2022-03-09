<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comments';
    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'comment_id',
        'user_id', 
        'auction_id',
        'content'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
