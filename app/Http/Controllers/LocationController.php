<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Location;
use App\Models\Collection;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('locations/index', [
            'locations' => Location::orderBy('label')->whereIn('origin', ['salsah', 'pia'])->get()
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
        return view('locations/show', [
            'location' => Location::find($id),
            'collections' => Collection::where('origin', 'pia')->latest()->take(20)->get()
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
        return view('locations/edit', [
            'location' => Location::find($id),
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
        $location = Location::find($id);

        $location->label = $request->label;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->geonames_id = $request->geonames_id;
        $location->geonames_url = 'https://sws.geonames.org/'.$request->geonames_id;

        $location->save();

        return redirect()->route('locations.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = Location::find($id);

        if($location->images->count()) {
            foreach ($location->images as $key => $image) {
                $image->location_id = null;
                $image->save();
            }
        }

        Location::destroy($id);
        return redirect('/');
    }
}
