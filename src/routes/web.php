<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReviewController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/register', [AuthController::class, 'store']);
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::get('items/search', [ItemController::class, 'search']);
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'detail']);
Route::post('/favorite/{item_id}', [ItemController::class, 'favorite']);

Route::middleware('auth')->group(function() {
    Route::post('/logout', [AuthController::class, 'destroy']);
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::put('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);
    Route::get('/purchase/address/{item_id}' , [PurchaseController::class, 'editAddress']);
    Route::post('/purchase/address/{item_id}' , [PurchaseController::class, 'updateAddress']);
    Route::post('/comment/{item_id}', [ItemController::class, 'comment']);
    Route::get('/sell' , [ItemController::class, 'create']);
    Route::post('/sell' , [ItemController::class, 'store']);
    Route::get('/mypage' , [ProfileController::class, 'profile']);
    Route::get('/chat/{purchase}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{purchase}', [ChatController::class, 'store'])->name('chat.store');
    Route::put('/chat/message/{message}', [ChatController::class, 'update'])->name('chat.update');
    Route::delete('/chat/message/{message}', [ChatController::class, 'destroy'])->name('chat.destroy');
    Route::post('/purchase/{purchase}/review', [ReviewController::class, 'store'])->name('review.store');

});



