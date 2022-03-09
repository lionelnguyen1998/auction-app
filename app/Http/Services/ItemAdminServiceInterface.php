<?php

namespace App\Http\Services;

interface ItemAdminServiceInterface
{
    public function getCountItems($datas);
    public function getListItems($datas);
    public function getAllItems();
}
