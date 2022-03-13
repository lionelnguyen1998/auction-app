<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $primaryKey = 'contact_id';

    protected $fillable = [
        'contact_id', 
        'email',
        'name',
        'phone',
        'content'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
