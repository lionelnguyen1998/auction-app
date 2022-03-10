<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;

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
Route::get('login', [UserController::class, 'index'])->name('loginUser');
Route::get('logout', [UserController::class, 'logout'])->name('logoutUser');
Route::get('register', [UserController::class, 'register'])->name('registerUser');
Route::post('login/store', [UserController::class, 'store'])->name('storeUserAccount');
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
        Route::get('create', [AuctionController::class, 'create'])->name('createAuction');
        Route::post('store', [AuctionController::class, 'store'])->name('insertAuction');
        Route::get('deny', [AuctionController::class, 'deny'])->name('auctionDeny');
    });

    //item
    Route::prefix('items')->group(function () {
        Route::get('create/{auctionId}/{categoryId}', [ItemController::class, 'create'])->name('createItem');
        Route::post('store', [ItemController::class, 'store'])->name('insertItem');
    });

    //Upload
    Route::post('upload/services', [UploadController::class, 'store'])->name('uploadFiles');

    //sendEmail when accept bid
    Route::get('acceptBid/{bidId}', [AuctionController::class, 'acceptBid'])->name('acceptBid');

    //chat when negotiate
    Route::get('chat', [ChatController::class, 'chat'])->name('chat');  

});