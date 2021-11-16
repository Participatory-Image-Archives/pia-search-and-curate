<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\KeywordController;

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
    return view('frontend/home');
});

Route::resource('collections', CollectionController::class);
Route::resource('images', ImageController::class);
Route::resource('keywords', KeywordController::class);


Route::get('/light-table', function () {
    return view('frontend/light-table');
});

Route::get('/map', function () {
    return view('frontend/map');
});

