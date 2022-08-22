<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => ['api'],
    'prefix' => 'auth'

], function ($router) {

    Route::post("register", [AuthController::class, ('register')]);
    Route::post('login', [AuthController::class, ('login')]);
    Route::post('update-profile', [AuthController::class, ('updateProfile')]);
    Route::post('logout', [AuthController::class, ('logout')]);
    Route::post('refresh', [AuthController::class, ('refresh')]);
    Route::post('me', [AuthController::class, ('me')]);

});
Route::group([
    'middleware' => ["api"],
    "prefix" => 'v1/book'
], function() {
    Route::get('all', [BookController::class, ("show")]);
    Route::post("create", [BookController::class, ("store")]);
    Route::put("update", [BookController::class, ("edit")]);
    Route::delete("delete/{book}", [BookController::class, ('destroy')]);
});