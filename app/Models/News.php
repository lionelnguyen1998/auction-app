<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news';
    protected $primaryKey = 'new_id';

    protected $fillable = [
        'new_id',
        'user_id',
        'title',
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

    public function listNews($request)
    {
        $page = $request['page'];
        $perPage = $request['per_page'];
        
        $news = News::orderBy('created_at', 'DESC')
            ->forPage($page, $perPage)
            ->get();

        return $news;
    }
}
