<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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

    $query = Image::whereIn('id', $ids)
                ->select('id', 'salsah_id', 'title', 'location_id');

    $results = $query->get();

    if($request->exists('geo')){
        foreach($results as $k => $r) {
            $r->longitude = $r->location()->longitude;
            $r->latitude = $r->location()->latitude;
        }
    }

    return response()->json($results);
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('admin-users')->name('admin-users/')->group(static function() {
            Route::get('/',                                             'AdminUsersController@index')->name('index');
            Route::get('/create',                                       'AdminUsersController@create')->name('create');
            Route::post('/',                                            'AdminUsersController@store')->name('store');
            Route::get('/{adminUser}/impersonal-login',                 'AdminUsersController@impersonalLogin')->name('impersonal-login');
            Route::get('/{adminUser}/edit',                             'AdminUsersController@edit')->name('edit');
            Route::post('/{adminUser}',                                 'AdminUsersController@update')->name('update');
            Route::delete('/{adminUser}',                               'AdminUsersController@destroy')->name('destroy');
            Route::get('/{adminUser}/resend-activation',                'AdminUsersController@resendActivationEmail')->name('resendActivationEmail');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::get('/profile',                                      'ProfileController@editProfile')->name('edit-profile');
        Route::post('/profile',                                     'ProfileController@updateProfile')->name('update-profile');
        Route::get('/password',                                     'ProfileController@editPassword')->name('edit-password');
        Route::post('/password',                                    'ProfileController@updatePassword')->name('update-password');
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('images')->name('images/')->group(static function() {
            Route::get('/',                                             'ImagesController@index')->name('index');
            Route::get('/create',                                       'ImagesController@create')->name('create');
            Route::post('/',                                            'ImagesController@store')->name('store');
            Route::get('/{image}/edit',                                 'ImagesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'ImagesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{image}',                                     'ImagesController@update')->name('update');
            Route::delete('/{image}',                                   'ImagesController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('locations')->name('locations/')->group(static function() {
            Route::get('/',                                             'LocationsController@index')->name('index');
            Route::get('/create',                                       'LocationsController@create')->name('create');
            Route::post('/',                                            'LocationsController@store')->name('store');
            Route::get('/{location}/edit',                              'LocationsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'LocationsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{location}',                                  'LocationsController@update')->name('update');
            Route::delete('/{location}',                                'LocationsController@destroy')->name('destroy');
        });
    });
});