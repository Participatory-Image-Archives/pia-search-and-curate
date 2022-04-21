<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agent;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Format;
use App\Models\Image;
use App\Models\ModelType;
use App\Models\Keyword;
use App\Models\ObjectType;
use App\Models\Place;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = [
            'image' => Image::find($id)
        ];

        if($request->cid && $request->iid){
            $collection = Collection::find($request->cid);
            $index = 0;
            
            foreach ($collection->images as $key => $image) {
                if($image->id == $request->iid) {
                    break;
                }
                $index++;
            }

            if($index > 0){
                $data['prev'] = $collection->images->get($index-1);
                $data['cid'] = $collection->id;
            }

            if($index < $collection->images()->count() - 1){
                $data['next'] = $collection->images->get($index+1);
                $data['cid'] = $collection->id;
            }
        }

        return view('images/show', $data);
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
            'agents' => Agent::all(),
            'places' => Place::whereIn('origin', ['salsah', 'pia'])->get(),
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
        $image->title = $request->title;
        $image->sequence_number = $request->sequence_number;

        $image->object_type_id = $request->object_type_id;
        $image->model_type_id = $request->model_type_id;
        $image->format_id = $request->format_id;

        $image->copyright_id = $request->copyright_id;
        $image->license = $request->license;

        if($request->append_copyright != '') {
            $copyright = Agent::create([
                'name' => $request->append_copyright
            ]);
            $image->copyright_id = $copyright->id;
        }

        $image->keywords()->sync($request->keywords);
        $image->collections()->sync($request->collections);

        $image->agents()->sync($request->people);

        if($request->append_agent != '') {
            $agent = Agent::create([
                'name' => $request->append_agent
            ]);
            $image->people()->attach($agent);
        }

        $image->place_id = $request->place_id;

        if($request->append_place != '') {
            $place = Place::create([
                'label' => $request->append_place,
                'origin' => 'pia'
            ]);
            $image->place_id = $place->id;
        }

        if($request->append_comment != '') {
            $comment = Comment::create([
                'comment' => $request->append_comment
            ]);
            $image->comments()->attach($comment);
        }

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
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function findSimilar(Request $request, $id) {

        $image = Image::find($id);
        $category = $request->input('category', 'localcolor');

        $url = 'http://pia-iiif.dhlab.unibas.ch/'.$image->base_path.'/'.$image->signature.'.jp2/full/480,/0/default.png';
        $img = file_get_contents($url);
        if ($img !== false){
            $base64 = 'data:image/png;base64,'.base64_encode($img);
        } else {
            $base64 = '';
        }

        // curl call by signature
        // curl -X POST "http://10.34.58.72:4567/api/v1/find/segments/similar" -H "accept: application/json" -H "Content-Type: application/json" -d '{"containers":[{"terms":[{"type":"ID","data":"i_SGV_10D_00500_1","categories":["localfeatures"]}]}]}'
        // curl -X POST "http://10.34.58.72:4567/api/v1/find/segments/similar" -H "accept: application/json" -H "Content-Type: application/json" -d '{"containers":[{"terms":[{"type":"BOOLEAN","data":"data:application/json;base64,W3siYXR0cmlidXRlIjoiZmVhdHVyZXNfdGFibGVfcGlhX21ldGEuY29sbGVjdGlvbiIsIm9wZXJhdG9yIjoiSU4iLCJ2YWx1ZXMiOlsiU0dWIDEwIl19XQ==","categories":["boolean"]}]}]}’
        // "type”:"IMAGE","data":"data:image/png;base64,...","categories":["localfeatures”]}
        // "data": "i_'.$image->signature.'_1",
        /*
        "terms": [
            {
                "type": "TEXT",
                "data": "dog",
                "categories": [
                    "visualtextcoembedding"
                ]
            }
        ]
        */
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://131.152.217.186:4567/api/v1/find/segments/similar',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "terms": [
                    {
                        "type": "IMAGE",
                        "data": "'.$base64.'",
                        "categories": [
                            "'.$category.'"
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

        foreach (json_decode($response)->results[0]->content as $key => $item) {
            $similar[] = ltrim(rtrim($item->key, '_1'), 'i_');
        }

        $similar = array_slice($similar, 0, 100);

        return view('images/similar', [
            'image' => $image,
            'images' => Image::whereIn('signature', $similar)->get(),
            'results' => $passed_threshold,
            'collections' => Collection::where('origin', 'pia')->latest()->take(20)->get()
        ]);
    }
}
