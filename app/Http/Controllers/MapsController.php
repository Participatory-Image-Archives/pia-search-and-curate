<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Map;
use App\Models\Location;

class MapsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
        $map = Map::create([
            'label' => $request->label,
            'tiles' => 1,
            'origin' => 'collection'
        ]);
        $map->mapLayers()->create(['label' => 'Layer 1']);
        $map->collections()->sync([$request->collections]);

        return redirect()->route('maps.images', ['id' => $map->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collection = Map::find($id)->collections->get(0);
        Map::destroy($id);
        return redirect()->route('collections.show', [$collection]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function images($id)
    {
        return view('maps/edit', [
            'map' => Map::find($id),
            'collection' => Map::find($id)->collections->get(0)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imagesUpdate(Request $request, $id)
    {
        $map = Map::find($id);
        $layer = $map->mapLayers->first();
        $layer->mapEntries()->delete();

        $markers = json_decode($request->markerdata);

        foreach($markers as $key => $marker) {
            $entry = $layer->mapEntries()->create([
                'label' => $marker->alt,
                'type' => 4,
                'image_id' => $marker->id
            ]);

            $location = Location::create([
                'label' => $marker->alt,
                'latitude' => $marker->coordinates->latitude,
                'longitude' => $marker->coordinates->longitude
            ]);

            $entry->location_id = $location->id;

            $entry->push();
        }

        return redirect()->route('maps.images', [$map]);
    }
}
