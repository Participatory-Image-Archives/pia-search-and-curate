<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

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

use App\Models\Image;
use App\Models\Keyword;

Route::get('/', function () {
    return view('frontend/home');
});

Route::get('/light-table', function () {
    return view('frontend/light-table');
});

Route::get('/map', function () {
    return view('frontend/map');
});

Route::get('/api/images', function(Request $request) {
    $terms = explode(' ', $request->input('q'));
    $query = Image::with(['keywords:id,label', 'collection']);

    foreach($terms as $k => $term) {
        $query->where(DB::raw('lower(images.title)'), 'like', '%' . strtolower($term) . '%');
    }

    $query->orWhereHas('comments', function($query) use ($terms)  {
        foreach($terms as $k => $term) {
            $query->where(DB::raw('lower(comments.comment)'), 'like', '%' . strtolower($term) . '%');
        }
    });

    $images = $query->get();

    return response()->json($images);
});

Route::get('/api/ids', function(Request $request) {
    $ids = explode(',', $request->input('ids'));

    $query = Image::with(['location']);

    $query->whereIn('salsah_id', $ids);
    //$query->select('images.id', 'images.salsah_id', 'images.title');

    $results = $query->get();

    return response()->json($results);
});