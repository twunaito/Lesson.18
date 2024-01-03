<?php

use Illuminate\Support\Facades\Route;

//追加
use App\Http\Controllers\PostsController;

use Illuminate\Support\Facades\Auth;

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

//アクセス時にログイン画面（login.blade.php）が表示されるように設定
Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

//使わない
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//トップ画面
Route::get('index', [PostsController::class, 'index'])->name('posts.index');

//投稿画面
Route::get('/create-form', [PostsController::class, 'createForm']);

//投稿処理
Route::post('post/create', [PostsController::class, 'create']);

//投稿編集画面
Route::get('post/{id}/update-form', [PostsController::class, 'updateForm']);

//編集処理
Route::post('post/update', [PostsController::class, 'update']);

//削除処理
Route::get('post/{id}/delete', [PostsController::class, 'delete']);

//あいまい検索
Route::get('/posts/search', [PostsController::class, 'search'])->name('posts.search');
