<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::group([
  'as' => 'comments.',
  'controller' => CommentController::class,
], function () {
  Route::get('comments', 'index')
    ->name('index');

  Route::get('comments/{comment}', 'show')
    ->name('show');

  Route::post('comments', 'store')
    ->name('store');

  Route::patch('comments/{comment}', 'update')
    ->name('update');

  Route::delete('comments/{comment}', 'destroy')
    ->name('destroy');
});