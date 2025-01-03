<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminPanelController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/admin/teams', [AdminPanelController::class, 'index']);
    Route::get('/admin/teams/{id}', [AdminPanelController::class, 'show']);
    Route::put('/admin/teams/{id}', [AdminPanelController::class, 'edit']); 
    Route::delete('/admin/teams/{id}', [AdminPanelController::class, 'destroy']);
    
});