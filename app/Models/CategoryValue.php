<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'category_values';
    protected $primaryKey = 'category_value_id';

    protected $fillable = [
        'category_value_id',
        'category_id',
        'icon',
        'type',
        'name',
        'name_en'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'category_value_id', 'category_value_id');
    }
}
