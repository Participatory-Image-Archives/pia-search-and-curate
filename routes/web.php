<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CollectionController;

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

Route::get(
    '/api/images',
    [FrontendController::class, 'searchImages']
)->name('api.searchImages');

Route::get(
    '/api/ids',
    [FrontendController::class, 'getImagesByIds']
)->name('api.getImagesByIds');

Route::get(
    '/api/collection',
    [FrontendController::class, 'getCollection']
)->name('api.getCollection');

Route::resource('collections', CollectionController::class);

Route::get('/', function () {
    return view('frontend/home');
});

Route::get('/light-table', function () {
    return view('frontend/light-table');
});

Route::get('/map', function () {
    return view('frontend/map');
});

