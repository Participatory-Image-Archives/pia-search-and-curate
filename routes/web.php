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
use App\Models\Collection;
use App\Models\Comment;

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
    $image_query = Image::with([]);
    $comment_query = Comment::with(['images']);
    $image_ids = [];

    foreach($terms as $k => $term) {
        $image_query->where(DB::raw('lower(images.title)'), 'like', '%' . strtolower($term) . '%');
        $comment_query->where(DB::raw('lower(comments.comment)'), 'like', '%' . strtolower($term) . '%');
    }

    $images = $image_query->get();
    $comments = $comment_query->get();

    foreach($images as $k => $image) {
        if(!in_array($image->id, $image_ids)) {
            $image_ids[] = $image->id;
        }
    }
    foreach($comments as $k => $comment) {
        foreach($comment->images as $k => $image) {
            if(!in_array($image->id, $image_ids)) {
                $image_ids[] = $image->id;
            }
        }
    }

    $images = Image::with(['keywords:id,label', 'collections:signature,origin'])
                ->whereIn('id', $image_ids)
                ->select('images.id', 'images.salsah_id', 'images.signature', 'images.title')
                ->get();

    return response()->json($images);
});

Route::get('/api/ids', function(Request $request) {
    $image_ids = explode(',', $request->input('ids'));

    $images = Image::with(['collections:signature,origin', 'location'])
                ->whereIn('salsah_id', $image_ids)
                ->select('images.id', 'images.salsah_id', 'images.signature', 'images.title')
                ->get();

    return response()->json($images);
});