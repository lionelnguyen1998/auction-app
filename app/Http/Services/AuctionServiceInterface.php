<?php

namespace App\Http\Services;

interface AuctionServiceInterface
{
    public function getDetailAuctions($datas);
    public function getSellingUser($datas);
    public function getMaxPrice($datas);
    public function getBids($datas);
    public function getComments($datas);
    public function getInfor($datas);
    public function getCategoryValueName($datas);
    public function getGeneralInfo();
}
