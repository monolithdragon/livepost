<?php

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Route;

if ( App::environment('local') ) {
    Route::get('/playground', function () {
        $user = User::factory()->make();
        Mail::to($user)->send(new WelcomeMail($user));
        return null;
    });
}