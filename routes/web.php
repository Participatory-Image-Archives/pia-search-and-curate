<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
    $query = DB::connection('pia')->table('images');

    foreach($terms as $k => $term) {
        $query->where(DB::raw('lower(images.title)'), 'like', '%' . strtolower($term) . '%');
    }

    $query->join('collections as c', 'c.id', '=', 'images.collection_id');
    $query->select('images.*', 'c.label as collection');

    return response()->json($query->get());
});

Route::get('/api/ids', function(Request $request) {
    $ids = explode(',', $request->input('ids'));

    $query = Image::query();
    $query->whereIn('salsah_id', $ids);
    $query->select('id', 'salsah_id', 'title');
    $results = $query->get();

    return response()->json($results);
});