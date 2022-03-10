<?php

namespace App\Http\Services;

interface ItemServiceInterface
{
    public function getCountItems($datas);
    public function getListItems($datas);
    public function getAllItems();
}
