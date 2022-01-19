<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Image;
use App\Models\Collection;
use App\Models\Keyword;
use App\Models\Person;
use App\Models\Location;
use App\Models\ModelType;
use App\Models\ObjectType;
use App\Models\Format;

class ImageController extends Controller
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
        return view('images/show', [
            'image' => Image::find($id)
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
        return view('images/edit', [
            'image' => Image::find($id),
            'collections' => Collection::all(),
            'keywords' => Keyword::all(),
            'people' => Person::all(),
            'locations' => Location::all(),
            'model_types' => ModelType::all(),
            'object_types' => ObjectType::all(),
            'formats' => Format::all(),
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
        $image = Image::find($id);

        $image->salsah_id = $request->salsah_id;
        $image->oldnr = $request->oldnr;
        $image->signature = $request->signature;
        $image->original_title = $request->original_title;
        $image->sequence_number = $request->sequence_number;

        $image->object_type_id = $request->object_type_id;
        $image->model_type_id = $request->model_type_id;
        $image->format_id = $request->format_id;

        $image->keywords()->sync($request->keywords);
        $image->collections()->sync($request->collections);
        $image->people()->sync($request->people);

        $image->location_id = $request->location_id;

        $image->save();

        return redirect()->route('images.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Image::destroy($id);
        return redirect('/');
    }

    /**
     * Find similar images from the collection.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function findSimilar($id) {

        $image = Image::find($id);

        // curl call by signature
        // curl -X POST "http://10.34.58.72:4567/api/v1/find/segments/similar" -H "accept: application/json" -H "Content-Type: application/json" -d '{"containers":[{"terms":[{"type":"ID","data":"i_SGV_10D_00500_1","categories":["localfeatures"]}]}]}'
        // curl -X POST "http://10.34.58.72:4567/api/v1/find/segments/similar" -H "accept: application/json" -H "Content-Type: application/json" -d '{"containers":[{"terms":[{"type":"BOOLEAN","data":"data:application/json;base64,W3siYXR0cmlidXRlIjoiZmVhdHVyZXNfdGFibGVfcGlhX21ldGEuY29sbGVjdGlvbiIsIm9wZXJhdG9yIjoiSU4iLCJ2YWx1ZXMiOlsiU0dWIDEwIl19XQ==","categories":["boolean"]}]}]}’

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://10.34.58.72:4567/api/v1/find/segments/similar',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "containers": [
                    {
                        "terms": [
                            {
                                "type": "ID",
                                "data": "i_'.$image->signature.'_1",
                                "categories": [
                                    "localfeatures"
                                ]
                            }
                        ]
                    }
                ]
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
          
        $response = curl_exec($curl);

        $passed_threshold = array_filter(json_decode($response)->results[0]->content, function ($item) {
            return ($item->value > 0.7);
        });

        $similar = [];

        foreach ($passed_threshold as $key => $item) {
            $similar[] = ltrim(rtrim($item->key, '_1'), 'i_');
        }

        return view('images/similar', [
            'image' => $image,
            'similar' => Image::whereIn('signature', $similar)->get(),
            'results' => $passed_threshold
        ]);
    }
}
