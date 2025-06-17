<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('/notes', [NoteController::class, 'index']);
    Route::get('/note/{note}', [NoteController::class, 'show']);
    Route::get('/note/title/{title}', [NoteController::class, 'findByTitle']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::put('/notes/{note}', [NoteController::class, 'update']);
    Route::delete('/notes/{note}', [NoteController::class, 'destroy']);
});