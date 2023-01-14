<?php

use App\Http\Controllers\PostsApiController;
use App\Http\Controllers\UsersApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/get-posts', [PostsApiController::class, 'inserApiPosts']);

Route::group(['prefix'=>'posts'], function () {
    Route::get('/{id}', [PostsApiController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/top', [PostsApiController::class, 'top']);
});

Route::group(['prefix'=>'users'], function () {
    Route::get('/', [UsersApiController::class, 'users']);
});