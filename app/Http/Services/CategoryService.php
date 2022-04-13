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
       $data = [
           'categories' => $listCategories->map(function($category) {
               return [
                   'category_id' => $category->category_id,
                   'name' => $category->name,
                   'image' => $category->image,
                   'type' => $category->type
               ];
           }),
       ];
       return $data;
    }
}
