<?php

use App\Http\Controllers\UrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'urls'], function () {
    Route::get('/', [UrlController::class, 'index'])->name('urls.index');
    Route::get('/{hash}', [UrlController::class, 'redirectToOriginal'])->whereAlphaNumeric(['hash']);
    Route::post('/store', [UrlController::class, 'store']);
});
