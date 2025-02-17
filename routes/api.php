<?php

use App\Http\Controllers\Question;
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

                Route::post('store', Question\StoreController::class)
                    ->name('questions.store');

                Route::put('/{question}', Question\UpdateController::class)
                    ->name('questions.update');

                Route::delete('/{question}', Question\DeleteController::class)
                    ->name('questions.delete');

                Route::delete('/{question}/archive', Question\ArchiveController::class)
                    ->name('questions.archive');
            });
    });
