<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\KeywordController;
use App\Models\Image;

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

Route::get('/collections/{id}/export',
    [FrontendController::class, 'exportCollection'])->name('collections.export');

Route::get('/light-table', function () {
    return view('frontend/light-table');
});

Route::get('/map', function () {
    return view('frontend/map');
});

Route::get('/dangerous-activity', function () {

    $images = Image::where('base_path', '=', '')->get();

    print($images->count().' empty<br>');

    $images = Image::where('base_path', '=', 'SGV_10')->get();

    print($images->count().' SGV_10<br>');

    $images = Image::where('base_path', '=', 'SGV_12')->get();

    print($images->count().' SGV_12<br>');

    $images = Image::all();

    print($images->count());

    /*foreach ($images as $i_key => $image) {
        foreach ($image->collections as $c_key => $collection) {
            if ($image->base_path == '' && $collection->origin == 'salsah') {
                $image->base_path = $collection->signature;
                $image->save();
            }
        }
    }*/
});

