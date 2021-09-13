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
    $query = Image::query();

    foreach($terms as $k => $term) {
        $query->where(DB::raw('lower(title)'), 'like', '%' . strtolower($term) . '%');
    }

    $query->select('id', 'salsah_id', 'title');
    $results = $query->get();

    return response()->json($results);
});

Route::get('/api/ids', function(Request $request) {
    $ids = explode(',', $request->input('ids'));

    $query = Image::query();
    $query->whereIn('salsah_id', $ids);
    $query->select('id', 'salsah_id', 'title');
    $results = $query->get();

    return response()->json($results);
});