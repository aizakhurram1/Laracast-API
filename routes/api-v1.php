<?php

use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




//Route::resource('tickets',TicketController::class);
Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('tickets', TicketController::class)->except(['update']);
    Route::put('tickets/{ticket}', [TicketController::class, 'replace']);
    Route::patch('tickets/{ticket}', [TicketController::class, 'update']);

    Route::apiResource('authors', AuthorsController::class);
    Route::apiResource('authors.tickets', AuthorTicketsController::class)->except(['update']);
    Route::put('authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'replace']);
    Route::patch('authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'update']);
    Route::delete('authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'destroy']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

//session vs cookies