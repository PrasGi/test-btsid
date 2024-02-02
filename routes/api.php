<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/checklist', [ChecklistController::class, 'index']);
    Route::post('/checklist', [ChecklistController::class, 'store']);
    Route::delete('/checklist/{checklist}', [ChecklistController::class, 'destroy']);

    Route::get('/checklist/{checklist}/item', [ItemController::class, 'index']);
    Route::post('/checklist/{checklist}/item', [ItemController::class, 'store']);
    Route::get('/checklist/{checklist}/item/{item}', [ItemController::class, 'getItemByChecklistIdItemId']);
    Route::put('/checklist/{checklist}/item/{item}', [ItemController::class, 'updateStatusItemByChecklistIdItemId']);
    Route::delete('/checklist/{checklist}/item/{item}', [ItemController::class, 'destroy']);
    Route::put('/checklist/{checklist}/item/rename/{item}', [ItemController::class, 'renameItemByChecklistIdItemId']);
});
