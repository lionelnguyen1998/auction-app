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
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ChatController;

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
    Route::post('signup', [UserController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('loginfailed', function () {
        return [
            "code" => 1004,
            "message" => "まだログインではありません",
            "data" => null,
        ];
    })->name('loginfailed');

    //Slider
    Route::get('slider', [HomeController::class, 'slider']);
    
    //Auction
    Route::prefix('auctions')->group(function () {
        Route::get('/{statusId}', [AuctionController::class, 'index']);
        Route::get('/listAuctionsByStatus/{statusId}', [AuctionController::class, 'listAuctionByStatus']);
        Route::get('/listAuctions/{typeId}/{statusId}', [AuctionController::class, 'listAuctionByType']);
        Route::get('/detail/{auctionId}', [AuctionController::class, 'detail']);
        Route::get('/update/status', [AuctionController::class, 'uploadStatus']);
        Route::get('/detail1/{auctionId}', [AuctionController::class, 'detail1']);
        Route::get('/listAuctionOfCategory/{categoryId}/{statusId}', [AuctionController::class, 'listAuctionOfCategory']);
        Route::get('/maxBid/{auctionId}', [AuctionController::class, 'maxBid']);
        Route::get('/listAuctionsByUserK/{userId}/{statusId}', [AuctionController::class, 'listAuctionsByUserK']);
    });

    //total likes of auctions
    Route::get('totalLikes/{auctionId}', [AuctionController::class, 'totalLikes']);

    //contact
    Route::post('/contactUs', [UserController::class, 'contactUs']);

    //list comment
    Route::get('/comments/{auctionId}', [AuctionController::class, 'listComments']);

    //list bids
    Route::get('/bids/{auctionId}', [AuctionController::class, 'listBids']);

    //category
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    //brand
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
    });
    
    //uploadFiles
    Route::post('/uploadFiles', [UploadController::class, 'uploads']);

    //search
    Route::get('/search', [SearchController::class, 'search']);

    //news
    Route::prefix('news')->group(function () {
        Route::get('/', [NewController::class, 'news']);
        Route::get('/read/{newId}', [NewController::class, 'read']);
    });
    
    Route::middleware(['auth'])->group(function () { 
        //Account
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('edit', [UserController::class, 'edit']);
        Route::post('changepass', [UserController::class, 'changePassword']);
        Route::get('info', [UserController::class, 'info']);

        //auctions
        Route::prefix('auctions')->group(function () {
            Route::get('/listAuctionsByUser/{statusId}', [AuctionController::class, 'listAuctionsByUser']);
            Route::post('/create', [AuctionController::class, 'create']);
            Route::get('/info/{auctionId}', [AuctionController::class, 'info']);
            Route::delete('/delete/{auctionId}', [AuctionController::class, 'delete']);
            Route::post('/edit/{auctionId}', [AuctionController::class, 'edit']);
            
        });

        //items
        Route::prefix('items')->group(function () {
            Route::post('/create/{auctionId}', [ItemController::class, 'create']);
            Route::post('/edit/{itemId}', [ItemController::class, 'edit']);
            Route::get('/categories', [ItemController::class, 'listCategoryOfItem']);
            Route::get('/listBuy/{categoryId}', [ItemController::class, 'listBuy']);
            Route::get('/detail/{itemId}', [ItemController::class, 'detail']);
        });

        //Commnents
        Route::prefix('comments')->group(function () {
            Route::post('/create/{auctionId}', [AuctionController::class, 'comments']);
            Route::post('/delete/{commentId}', [AuctionController::class, 'deleteComment']);
        });

         //Bid
        Route::prefix('bids')->group(function () {
            Route::post('/create/{auctionId}', [AuctionController::class, 'bids']);
        });

        //accept bid => send report
        Route::prefix('accept')->group(function () {
            Route::post('/{auctionId}', [AuctionController::class, 'accept']);
            Route::get('/read/{itemId}', [NewController::class, 'readAccept']);
        });

        //Like
        Route::post('updateLike/{auctionId}', [AuctionController::class, 'updateLike']);
        Route::get('likes/{statusId}', [AuctionController::class, 'listLikes']);
        Route::get('listLikes', [AuctionController::class, 'totalLikeOfUser']);

        //upload file
        Route::post('/uploadFile', [UploadController::class, 'upload']);

        //notifications reject auction
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NewController::class, 'notifications']);
            Route::get('read/{auctionDenyId}', [NewController::class, 'reason']);
            Route::post('/delete/{auctionId}', [NewController::class, 'deleteNotification']);
        });

        

        //Chat 
        Route::prefix('chat')->group(function () {
            Route::get('/', [ChatController::class, 'index']);
            Route::post('/conversation/{userReceiveId}', [ChatController::class, 'conversation']);
            Route::post('/message', [ChatController::class, 'createMessage']);
            Route::get('/listMessages/{chatId}', [ChatController::class, 'listMessages']);
            Route::get('/info/{chatId}', [ChatController::class, 'info']);
        });

        //all user
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'listUsers']);
        });
    });
});
