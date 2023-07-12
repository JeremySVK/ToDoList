<?php

use App\Http\Controllers\Api\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('task')->controller(TaskController::class)->group(function () {

    Route::get('/', 'index');
    Route::get('/tag-list', 'getTags');
    Route::get('/status-list', 'getStatuses');
    Route::post('/store', 'store');

    Route::post('/edit-status/{id}', 'update')->name('editTaskStatus');
    Route::post('/edit-task/{id}', 'update')->name('editTask');
    Route::get('/detail/{id}', 'show');
    Route::post('/delete/{id}', 'destroy');
});
