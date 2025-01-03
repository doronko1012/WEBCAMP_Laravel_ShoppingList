<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\CompletedShoppingListController;
//use App\Http\Controllers\TestController;

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\UserController as AdminUserController; 

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

// 会員登録
Route::prefix('/user')->group(function () {
    Route::get('/register', [UserController::class, 'index'])->name('front.user.register');
    Route::post('/register', [UserController::class, 'register'])->name('front.user.register.post');
});

// 認可処理（middlewareを挟むことで認可を行うコントローラーへのアクションメソッド）
Route::middleware(['auth'])->group(function () {
    Route::prefix('/shopping_list')->group(function () {
        Route::get('/list', [ListController::class, 'list']);
        Route::post('/register', [ListController::class, 'register']);
        Route::delete('/delete/{shopping_list_id}', [ListController::class, 'delete'])->whereNumber('shopping_list_id')->name('delete');
        Route::post('/complete/{shopping_list_id}', [ListController::class, 'complete'])->whereNumber('shopping_list_id')->name('complete');
    });
    // 購入済み「買うもの」一覧
    Route::get('/completed_shopping_list/list', [CompletedShoppingListController::class, 'list']);
    // ログアウト
    Route::get('/logout', [AuthController::class, 'logout']);
});

// 管理画面
Route::prefix('/admin')->group(function () {
    Route::get('', [AdminAuthController::class, 'index'])->name('admin.index');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::middleware(['auth:admin'])->group(function () {
        // 認可処理
        Route::get('/top', [AdminHomeController::class, 'top'])->name('admin.top');
        Route::get('/user/list', [AdminUserController::class, 'list'])->name('admin.user.list');
        // ログアウト
        Route::get('/logout', [AdminAuthController::class, 'logout']);
    });
});