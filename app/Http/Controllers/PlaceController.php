<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Place;
use App\Models\Collection;
use App\Models\Image;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $places = Place::orderBy('label')->whereIn('origin', ['salsah', 'pia'])->get();
        $codes = [];
        $levels = [];

        foreach ($places as $key => $place) {
            if(isset($codes[$place->geonames_code]) && $place->geonames_code != '') {
                $codes[$place->geonames_code] += 1;
            } else {
                $codes[$place->geonames_code] = 1;
            }

            if(isset($levels[$place->geonames_division_level]) && $place->geonames_division_level != '') {
                $levels[$place->geonames_division_level] += 1;
            } else {
                $levels[$place->geonames_division_level] = 1;
            }
        }

        ksort($codes);
        ksort($levels);

        return view('places/index', [
            'places' => $places,
            'place_count' => Image::where('place_id', null)->count(),
            'codes' => $codes,
            'levels' => $levels
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('places/show', [
            'place' => Place::find($id),
            'collections' => Collection::where('origin', 'pia')->latest()->take(20)->get(),
            'image_count' => Place::find($id)->images()->count()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('places/edit', [
            'place' => Place::find($id),
            'collections' => Collection::where('origin', 'pia')->latest()->take(20)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $place = Place::find($id);

        $place->label = $request->label;
        $place->latitude = $request->latitude;
        $place->longitude = $request->longitude;
        $place->geonames_id = $request->geonames_id;
        $place->geonames_url = 'https://sws.geonames.org/'.$request->geonames_id;

        $place->save();

        return redirect()->route('places.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $place = Place::find($id);

        if($place->images->count()) {
            foreach ($place->images as $key => $image) {
                $image->place_id = null;
                $image->save();
            }
        }

        Place::destroy($id);
        return redirect('/');
    }
}
