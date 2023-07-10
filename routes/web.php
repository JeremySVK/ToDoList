<?php

use App\Http\Controllers\TaskController;
use App\Http\Middleware\TestMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([TestMiddleware::class])->group(function () {

    Route::get('/home', [TaskController::class, 'index'])->name('home');
    Route::post('/store-task', [TaskController::class, 'store'])->name('storeTask');
    Route::get('/detail/{id}', [TaskController::class, 'show'])->name('taskDetail');
    Route::delete('/delete-task/{id}', [TaskController::class, 'destroy'])->name('deleteTask');
    Route::post('/edit-task-status/{id}', [TaskController::class, 'update'])->name('editTaskStatus');
    Route::post('/edit-task/{id}', [TaskController::class, 'update'])->name('editTask');

});

