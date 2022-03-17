<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace' => 'Api'], function(){
    //User
    Route::post('register', [UserController::class, 'register'])->name('register');
    Route::post('login', [UserController::class, 'login'])->name('loginUser');

    //Auction
    Route::prefix('auctions')->group(function () {
        Route::get('/', [AuctionController::class, 'index'])->name('listAuction');
        Route::get('/detail/{auctionId}', [AuctionController::class, 'detail'])->name('detailAuctions');
        Route::post('/create', [AuctionController::class, 'create'])->name('createAuctions');
        Route::delete('/delete/{auctionId}', [AuctionController::class, 'delete'])->name('deleteAuctions');
        Route::put('/edit/{auctionId}', [AuctionController::class, 'edit'])->name('editAuctions');
        //khong can thiet lam
        Route::get('/listAuctions/{typeId}', [AuctionController::class, 'listAuctionByType'])->name('listAuctionByType');
        Route::get('/listAuctionsByUser/{userId}', [AuctionController::class, 'listAuctionsByUser'])->name('listAuctionsByUser');
    });

    //Commnents
    Route::prefix('comments')->group(function () {
        Route::post('/create/{auctionId}', [AuctionController::class, 'comments'])->name('createComments');
    });

    Route::middleware(['auth_api'])->group(function () { 
        Route::prefix('auctions')->group(function () {
            Route::post('/create', [AuctionController::class, 'create'])->name('createAuctions');
            Route::delete('/delete/{auctionId}', [AuctionController::class, 'delete'])->name('deleteAuctions');
            Route::put('/edit/{auctionId}', [AuctionController::class, 'edit'])->name('editAuctions');
        });
    });
});
