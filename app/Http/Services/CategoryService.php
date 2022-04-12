<?php

namespace App\Http\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Models\CategoryValue;

class CategoryService implements CategoryServiceInterface
{
    public function getCategoryList()
    {
       $listCategories = Category::all();
       return $listCategories;
    }
}
