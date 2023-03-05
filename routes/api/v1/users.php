<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
  'as' => 'users.',
  'controller' => UserController::class,
], function () {
  Route::get('users', 'index')
    ->name('index');

  Route::get('users/{user}', 'show')
    ->name('show');

  Route::post('users', 'store')
    ->name('store');

  Route::patch('users/{user}', 'update')
    ->name('update');

  Route::delete('users/{user}', 'destroy')
    ->name('destroy');
});