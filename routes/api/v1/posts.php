<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::group([
  'as' => 'posts.',
  'controller' => PostController::class,
], function () {
  Route::get('posts', 'index')
    ->name('index');

  Route::get('posts/{post}', 'show')
    ->name('show');

  Route::post('posts', 'store')
    ->name('store');

  Route::patch('posts/{post}', 'update')
    ->name('update');

  Route::delete('posts/{post}', 'destroy')
    ->name('destroy');
});