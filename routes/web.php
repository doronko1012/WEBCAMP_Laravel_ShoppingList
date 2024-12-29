<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// 買い物リスト
Route::get('/', [AuthController::Class, 'index'])->name('front.index');
Route::post('/login', [AuthController::Class, 'login']);

// 認可処理（middlewareを挟むことで認可を行うコントローラーへのアクションメソッド）
Route::middleware(['auth'])->group(function () {
    Route::get('/shopping_list/list', [ListController::class, 'list']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

// テスト入力
Route::get('/test', [TestController::class, 'index']);
Route::post('/test/input', [TestController::class, 'input']);
