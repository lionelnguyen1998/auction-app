<?php

namespace App\Http\Services;

interface AuctionAdminServiceInterface
{
    public function getListAuctions();
    public function getDetailAuctions($datas);
    public function getSellingUser($datas);
    public function getMaxPrice($datas);
    public function getBids($datas);
    public function getComments($datas);
    public function getInfor($datas);
    public function getCategoryValueName($datas);
    public function getListAuctionsWait();
    public function getGeneralInfo();
}
