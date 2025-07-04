<?php

use App\Http\Controllers\Api\Authcontroller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [Authcontroller::class,  'login']);
Route::post('/register', [Authcontroller::class,  'register']);
Route::middleware('auth:sanctum')->post('/logout', [Authcontroller::class, 'logout']);
// Route::get('/tickets',  function() {

//     return Ticket::all();

// });
