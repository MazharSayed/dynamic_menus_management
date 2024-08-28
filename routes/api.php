<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/getMenus', [MenuController::class, 'index']);
Route::get('/menusDetails/{menu_id}', [MenuController::class, 'show']);
Route::post('/addMenus', [MenuController::class, 'store']);
Route::put('/updateMenus/{menu_id}', [MenuController::class, 'update']);
Route::delete('/deleteMenu/{menu_id}', [MenuController::class, 'destroy']);

