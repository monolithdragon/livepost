<?php

use App\Helpers\Routes\RouteHelper;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
  RouteHelper::includeRouteFiles(__DIR__.'/api/v1');
});