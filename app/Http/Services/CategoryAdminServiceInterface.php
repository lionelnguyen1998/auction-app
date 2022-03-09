<?php

namespace App\Http\Services;

interface CategoryAdminServiceInterface
{
    public function getCategoryList();
    public function getCategory($datas);
    public function categoryValidation($datas); 
}
