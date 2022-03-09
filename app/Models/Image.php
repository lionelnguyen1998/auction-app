<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'images';
    protected $primaryKey = 'image_id';

    protected $fillable = [
        'image_id',
        'item_id',
        'image'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}
