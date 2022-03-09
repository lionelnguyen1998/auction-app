<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\AuctionUserController;
use App\Http\Controllers\UploadUserController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//client
//login
Route::get('login', [UserLoginController::class, 'index'])->name('loginUser');
Route::get('logout', [UserLoginController::class, 'logout'])->name('logoutUser');
Route::get('register', [UserLoginController::class, 'register'])->name('registerUser');
Route::post('login/store', [UserLoginController::class, 'store'])->name('storeUserAccount');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//product 
Route::prefix('categories')->group(function () {
    Route::get('product/{typeId}', [ProductController::class, 'index'])->name('productOfCategory');
    Route::get('product/detail/{auctionId}', [ProductController::class, 'detail'])->name('detailAuctions');
    // Route::get('product/list/{auctionId}', [ProductController::class, 'list'])->name('listItemOfAuctions');
});

Route::middleware(['auth'])->group(function () {

    //bid
    Route::prefix('bids')->group(function () {
        Route::post('create', [BidController::class, 'create'])->name('insertBid');
    });

     //Comments
     Route::prefix('comments')->group(function () {
        Route::post('create', [CommentController::class, 'create'])->name('insertComment');
    });

    //Auctions
    Route::prefix('auctions')->group(function () {
        Route::get('create', [AuctionUserController::class, 'create'])->name('createAuction');
        Route::post('store', [AuctionUserController::class, 'store'])->name('insertAuction');
        Route::get('deny', [AuctionUserController::class, 'deny'])->name('auctionDeny');
    });

    //item
    Route::prefix('items')->group(function () {
        Route::get('create/{auctionId}/{categoryId}', [ItemController::class, 'create'])->name('createItem');
        Route::post('store', [ItemController::class, 'store'])->name('insertItem');
    });

    //Upload
    Route::post('upload1/services1', [UploadUserController::class, 'store'])->name('uploadUserFiles');

    //sendEmail when accept bid
    Route::get('acceptBid/{bidId}', [AuctionUserController::class, 'acceptBid'])->name('acceptBid');

    //chat when negotiate
    Route::get('chat', [ChatController::class, 'chat'])->name('chat');  

});