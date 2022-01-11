<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collection;
use App\Models\Image;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('collections/index', [
            'collections' => Collection::where('origin', '!=', 'salsah')->orderBy('label')->get()
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
        if($request->input('collection_id') != '') {
            $collection = Collection::find($request->input('collection_id'));
            $collection->label = $request->input('label');
            $collection->description = $request->input('description');
            $collection->save();
        } else {
            $collection = Collection::create([
                'label' => $request->input('label'),
                'origin' => 'pia'
            ]);
        }

        $collection->images()->sync(explode(',', $request->input('image_ids')));

        return redirect()->route('collections.show', [$collection]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('collections/show', [
            'collection' => Collection::find($id)
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
        Collection::destroy($id);
        return redirect()->route('collections.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request, $id)
    {
        if($files = $request->file('images')){
            foreach($files as $file){
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://pia-iiif.dhlab.unibas.ch/server/upload-pia.elua',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                // @TODO: fix those damn ssl problems
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => array(
                        'Datei'=> new \CURLFile(
                            $file->getPathName()
                        ),
                        'Cuse_sop' => 'yes'
                    ),
                ));

                $response = curl_exec($curl);

                $data = json_decode($response);

                $image = Image::create([
                    'label' => $data->signature,
                    'signature' => $data->signature,
                    'base_path' => 'upload',
                ]);

                $image->collections()->sync($id);

                $image->save();
            }
        }

        return redirect()->route('collections.show', [$id]);
    }
}
