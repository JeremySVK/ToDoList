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

Route::controller(TaskController::class)->group(function () {

    Route::middleware([TestMiddleware::class])->group(function () {

        Route::get('/home',  'index')->name('home');
        Route::post('/store-task', 'store')->name('storeTask');
        Route::get('/detail/{id}', 'show')->name('taskDetail');
        Route::delete('/delete-task/{id}', 'destroy')->name('deleteTask');
        Route::post('/edit-task-status/{id}', 'update')->name('editTaskStatus');
        Route::post('/edit-task/{id}',  'update')->name('editTask');

    });

});

Route::get('/task/{any?}', function () {
    return view('welcome');
})->where('any', '.*');

