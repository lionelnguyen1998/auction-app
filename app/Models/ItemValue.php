<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'item_values';
    protected $primaryKey = 'item_value_id';

    protected $fillable = [
        'item_value_id',
        'item_id',
        'category_value_id',
        'value',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function categoryValues()
    {
        return $this->belongsTo(CategoryValue::class, 'category_value_id', 'category_value_id');
    }
}
