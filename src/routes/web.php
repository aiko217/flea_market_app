<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;

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

Route::post('/register', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/logout', [AuthController::class, 'destroy']);
Route::get('items/search', [ItemController::class, 'search']);
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'detail']);

Route::middleware('auth')->group(function() {
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::put('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);
    Route::get('/purchase/address/{item_id}' , [PurchaseController::class, 'editAddress']);
    Route::post('/purchase/address/{item_id}' , [PurchaseController::class, 'updateAddress']);
    Route::post('/favorite/{item_id}', [ItemController::class, 'favorite']);
    Route::post('/comment/{item_id}', [ItemController::class, 'comment']);
    Route::get('/sell' , [ItemController::class, 'create']);
    Route::post('/sell' , [ItemController::class, 'store']);
    Route::get('/mypage' , [ProfileController::class, 'profile']);

});



