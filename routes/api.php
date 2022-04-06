<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\NewController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoryController;

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


Route::group(['middleware' => 'api'], function(){

    //User
    Route::post('register', [UserController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    //Slider
    Route::get('slider', [HomeController::class, 'slider'])->name('slider');
    
    //Auction
    Route::prefix('auctions')->group(function () {
        Route::get('/', [AuctionController::class, 'index'])->name('listAuction');
        Route::get('/{statusId}', [AuctionController::class, 'listAuctionByStatus']);
        Route::get('/detail/{auctionId}', [AuctionController::class, 'detail'])->name('detailAuctions');
        Route::get('/listAuctions/{typeId}', [AuctionController::class, 'listAuctionByType'])->name('listAuctionByType');
        Route::get('/listAuctionsByUser/{userId}', [AuctionController::class, 'listAuctionsByUser'])->name('listAuctionsByUser');
    });

    Route::post('/contactUs', [UserController::class, 'contactUs'])->name('contactUs');

    //list comment
    Route::get('/comments/{auctionId}', [AuctionController::class, 'listComments'])->name('listComments');

    //list bids
    Route::get('/bids/{auctionId}', [AuctionController::class, 'listBids'])->name('listBids');

    //New
    Route::prefix('news')->group(function () {
        Route::get('/', [NewController::class, 'index'])->name('listNews');
        Route::get('/read/{newId}', [NewController::class, 'read'])->name('readNews');
    });

    //category
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    Route::middleware(['auth'])->group(function () { 
        //Account
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('edit', [UserController::class, 'edit'])->name('edit');

        //auctions
        Route::prefix('auctions')->group(function () {
            Route::post('/create', [AuctionController::class, 'create'])->name('createAuctions');
            Route::delete('/delete/{auctionId}', [AuctionController::class, 'delete'])->name('deleteAuctions');
            Route::post('/edit/{auctionId}', [AuctionController::class, 'edit'])->name('editAuctions');
        });

        Route::prefix('items')->group(function () {
            Route::post('/create/{auctionId}', [ItemController::class, 'create'])->name('createItems');
            Route::post('/edit/{itemId}', [ItemController::class, 'edit'])->name('editItems');
        });

        //Commnents
        Route::prefix('comments')->group(function () {
            Route::post('/create/{auctionId}', [AuctionController::class, 'comments'])->name('createComments');
        });

         //Bid
        Route::prefix('bids')->group(function () {
            Route::post('/create/{auctionId}', [AuctionController::class, 'bids'])->name('createBids');
        });

        //Like
        Route::post('updateLike', [AuctionController::class, 'updateLike'])->name('updateLike');

        //read reject auctions report
        Route::get('reason/{auctionDenyId}', [NewController::class, 'reason'])->name('readReason');

        //accept bid => send report
        Route::prefix('accept')->group(function () {
            Route::post('/{auctionId}', [AuctionController::class, 'accept'])->name('acceptBid');
            Route::get('/read/{itemId}', [NewController::class, 'readAccept'])->name('readAcceptBid');
        });

        //upload file
        Route::post('/uploadFile', [UploadController::class, 'upload'])->name('uploadFile');

        //update auction status
        Route::get('/updateStatus', [AuctionController::class, 'updateStatus'])->name('updateStatus');

        //negotiate by chat box

    });
});
