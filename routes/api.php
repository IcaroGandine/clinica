<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LinkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/links/getAll', [LinkController::class, 'getAll']);
Route::get('/links/getByFilter', [LinkController::class, 'getByFilter']);
Route::get('/links/summary', [LinkController::class, 'getSummary']);
Route::post('/links/create', [LinkController::class, 'create']);
Route::put('/links/increment-clicks/{id}', [LinkController::class, 'incrementClicks']);
Route::delete('/links/delete/{id}', [LinkController::class, 'delete']);
