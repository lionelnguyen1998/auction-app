<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chat';
    protected $primaryKey = 'chat_id';

    protected $fillable = [
        'chat_id',
        'user_send_id',
        'user_receive_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function userReceive() {
        return $this->hasOne(User::class, 'user_id', 'user_receive_id');
    }

    public function userSend() {
        return $this->hasOne(User::class, 'user_id', 'user_send_id');
    }
}
