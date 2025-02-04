<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', fn () => ['status' => 'ok']);

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('questions')
            ->group(function () {
                Route::post('store', fn () => '')
                    ->name('question.store');
            });
    });
