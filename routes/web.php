<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\LocationController;

use App\Models\Image;
use App\Models\Collection;

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

Route::get('/', function (Request $request) {
    return view('frontend/home', [
        'tagcloud' => $request->has('tagcloud'),
        'collections' => Collection::where('origin', 'pia')->latest()->take(20)->get()
    ]);
});

Route::resource('collections', CollectionController::class);
Route::resource('images', ImageController::class);
Route::resource('keywords', KeywordController::class);
Route::resource('people', PersonController::class);
Route::resource('locations', LocationController::class);

Route::get('/collections/{id}/export',
    [FrontendController::class, 'exportCollection'])->name('collections.export');
Route::post('/collections/{id}/upload-image',
    [CollectionController::class, 'uploadImage'])->name('collections.uploadImage');
Route::post('/collections/{id}/upload-documents',
    [CollectionController::class, 'uploadDocuments'])->name('collections.uploadDocuments');
Route::get('/collections/{id}/map',
    [CollectionController::class, 'map'])->name('collections.map');
Route::get('/collections/{id}/copy',
    [CollectionController::class, 'copy'])->name('collections.copy');
Route::post('/collections/{id}/do-copy',
    [CollectionController::class, 'doCopy'])->name('collections.doCopy');

Route::get('/by-coordinates', function (Request $request) {
    return view('frontend/search-by-coordinates');
})->name('search.byCoordinates');
Route::get('/by-dates', function (Request $request) {
    return view('frontend/search-by-dates');
})->name('search.byDates');

Route::get('/fill-base-path', function () {

    $images = Image::whereNull('base_path')->get();

    print($images->count());

    foreach ($images as $i_key => $image) {
        foreach ($image->collections as $c_key => $collection) {
            if ($image->base_path == '' && $collection->origin == 'salsah') {
                $image->base_path = $collection->signature;
                $image->save();
            }
        }
    }
});

