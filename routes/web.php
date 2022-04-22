<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\CallEntryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DateController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PlaceController;

use App\Models\Collection;
use App\Models\Date;
use App\Models\Image;
use App\Models\Keyword;
use App\Models\Place;

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
    $images_otd = [];
    $total_count = Image::count();

    while (count($images_otd) <= 6) {
        srand(floor(time()/86400)+count($images_otd));
        $image = Image::find(rand(0, $total_count));
        $images_otd[] = $image;
    }

    return view('frontend/home', [
        'tagcloud' => $request->has('tagcloud'),
        'collections' => Collection::where('origin', 'pia')->latest()->take(20)->get(),
        'images_otd' => $images_otd
    ]);
});

Route::resource('collections', CollectionController::class);
Route::resource('images', ImageController::class);
Route::resource('keywords', KeywordController::class);
Route::resource('agents', AgentController::class);
Route::resource('places', PlaceController::class);
Route::resource('notes', NoteController::Class);
Route::resource('maps', MapController::Class);
Route::resource('calls', CallController::Class);
Route::resource('callentries', CallEntryController::Class);
Route::resource('dates', DateController::Class);

Route::get('/calls/{id}/entry', [CallController::class, 'entry'])->name('maps.images');

Route::get('/images/{id}/similar', [ImageController::class, 'findSimilar'])->name('images.similar');

Route::get('/maps/{id}/images', [MapController::class, 'images'])->name('maps.images');
Route::patch('/maps/{id}/imagesUpdate', [MapController::class, 'imagesUpdate'])->name('maps.imagesUpdate');

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
Route::get('/collections/{id}/timeline',
    [CollectionController::class, 'timeline'])->name('collections.timeline');

Route::get('/by-coordinates', function (Request $request) {
    return view('frontend/partials/search-by-coordinates');
})->name('search.byCoordinates');
Route::get('/by-dates', function (Request $request) {
    return view('frontend/partials/search-by-dates');
})->name('search.byDates');

/* statistics */
Route::get('/stats', function(Request $request) {
    $images_total = Image::count();

    $images_wo_date = Image::doesnthave('date')->count();
    $images_wo_location = Image::doesnthave('place')->count();
    $images_wo_keywords = Image::doesnthave('keywords')->count();

    $images_wo_date_location = Image::doesnthave('date')->where('place_id', null)->count();
    $images_wo_keywords_location = Image::doesnthave('keywords')->where('place_id', null)->count();
    $images_wo_date_keywords = Image::doesnthave('keywords')->doesnthave('dates')->count();
    $images_wo_date_keywords_location = Image::doesnthave('keywords')->doesnthave('date')->where('place_id', null)->count();

    $images_w_date_location = Image::has('date')->where('place_id', '!=', null)->count();
    $images_w_date_keywords = Image::has('date')->has('keywords')->count();
    $images_w_keywords_location = Image::has('keywords')->where('place_id', '!=', null)->count();
    $images_w_date_keywords_location = Image::has('keywords')->has('date')->where('place_id', '!=', null)->count();

    $images_w_acc_1 = Image::whereHas('date', function($q){
        $q->where('accuracy', '1');
    })->count();
    $images_w_acc_2 = Image::whereHas('date', function($q){
        $q->where('accuracy', '2');
    })->count();
    $images_w_acc_3 = Image::whereHas('date', function($q){
        $q->where('accuracy', '3');
    })->count();
    $images_w_date_range = Image::whereHas('date', function($q){
        $q->whereNotNull('end_date');
    })->count();

//number_format(100 / $images * $images_wo, 2)

    echo('<table cellpadding="8" border style="font-family: sans-serif;">');
    echo('<tr>');
    echo('<td>Total Image Count</td>');
    echo('<td>'.number_format($images_total, 0, '.', '\'').'</td>');
    echo('<td></td>');
    echo('</tr>');
    echo('<tr><td colspan="3">&nbsp;</td></tr>');
    echo('<tr>');
    echo('<td>Images without date</td>');
    echo('<td>'.number_format($images_wo_date, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_wo_date, 0, '.', '\'').'% (of total images)</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images without location</td>');
    echo('<td>'.number_format($images_wo_location, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_wo_location, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images without keywords</td>');
    echo('<td>'.number_format($images_wo_keywords, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_wo_keywords, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr><td colspan="3">&nbsp;</td></tr>');
    echo('<tr>');
    echo('<td>Images with neither date nor a location</td>');
    echo('<td>'.number_format($images_wo_date_location, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_wo_date_location, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images with neither keywords nor a location</td>');
    echo('<td>'.number_format($images_wo_keywords_location, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_wo_keywords_location, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images with neither date nor keywords</td>');
    echo('<td>'.number_format($images_wo_date_keywords, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_wo_date_keywords, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images without anything</td>');
    echo('<td>'.number_format($images_wo_date_keywords_location, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_wo_date_keywords_location, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr><td colspan="3">&nbsp;</td></tr>');
    echo('<tr>');
    echo('<td>Images with date and location</td>');
    echo('<td>'.number_format($images_w_date_location, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_w_date_location, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images with keywords and location</td>');
    echo('<td>'.number_format($images_w_date_location, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_w_keywords_location, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images with date and keywords</td>');
    echo('<td>'.number_format($images_w_date_location, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_w_date_keywords, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images with everything</td>');
    echo('<td>'.number_format($images_w_date_keywords_location, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_w_date_keywords_location, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr><td colspan="3">&nbsp;</td></tr>');
    echo('<tr>');
    echo('<td>Images with date accuracy 1, to the day</td>');
    echo('<td>'.number_format($images_w_acc_1, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_w_acc_1, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images with date accuracy 2, to the month</td>');
    echo('<td>'.number_format($images_w_acc_2, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_w_acc_2, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('<tr>');
    echo('<td>Images with date accuracy 3, to the year</td>');
    echo('<td>'.number_format($images_w_acc_3, 0, '.', '\'').'</td>');
    echo('<td>'.number_format(100 / $images_total * $images_w_acc_3, 0, '.', '\'').'%</td>');
    echo('</tr>');
    echo('</table>');
});

Route::get('/empty-title/{id}', function(Request $request, $id) {
    $collection = Collection::find($id);

    $headers = array(
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=file.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    );

    $columns = array(
        'salsah_id', 'oldnr', 'signature', 'title'
    );

    $callback = function() use ($collection, $columns)
    {
        $images = $collection->images->where('title', '');
        $file = fopen('php://output', 'w');

        fputcsv($file, $columns);

        foreach($images as $image) {

            $data = [
                $image->salsah_id,
                $image->oldnr,
                $image->signature,
                ''
            ];

            fputcsv($file, $data);
        }
        fclose($file);
    };

    return response()->streamDownload($callback, $collection->label.'-empty_title.csv');
});

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

