<?php

use App\Http\Controllers\Authcontroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/login',  [Authcontroller::class,  'login']);
