<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/',[ImageController::class, 'create']);
Route::get('/rainbow',[ImageController::class, 'rainbow']);

Route::get('/create', [ImageController::class, 'creationStation']);

Route::post('/create',[ImageController::class, 'store']); // store is a post-only function

Route::get('/logomaker',[ImageController::class, 'logoMaker']);
