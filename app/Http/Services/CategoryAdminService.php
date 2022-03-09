<?php

namespace App\Http\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryAdminService implements CategoryAdminServiceInterface
{
    public function getCategoryList()
    {
       $listCategories = Category::all();
       return $listCategories;
    }

    public function getCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        return $category;
    }

    public function categoryValidation($request) 
    {
        $rules = [
            'name_en' => "nullable|max:255",
            'icon' => "nullable|max:255",
            'count_number' => 'integer|min:1|max:10'
        ];

        if (isset($request["category_id"])) {
            $categoryId = $request["category_id"];
            $rules['name'] = "required|max:255|min:0|unique:categories,name,$categoryId,category_id,deleted_at,NULL";
        } else {
            $rules['name'] = "required|max:255|min:0|unique:categories,name";
        }

        $messages = [
            'required' => '必須項目が未入力です。',
            'max' => ':max文字以下入力してください。 ',
            'name.unique' => '既に使用されています。',
            'min' => ':attributeは少なくとも:minでなければなりません。',
            'integer' => '番号を入力してください',
        ];

        $attributes = [
            'name' => 'カテゴリー'
        ];

        $validated = Validator::make($request, $rules, $messages, $attributes);

        return $validated;
    }
}
