<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'sliders';
    protected $primaryKey = 'slider_id';

    protected $fillable = [
        'slider_id',
        'image',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
