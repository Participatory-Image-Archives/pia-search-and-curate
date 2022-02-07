<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PiaDoc;
use App\Models\Image;

class PiaDocsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('docs/index', [
            'docs' => PiaDoc::all()
        ]);
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
        $doc = PiaDoc::create([
            'label' => $request->label
        ]);
        $doc->collections()->sync($request->collections);

        return redirect()->route('docs.edit', [$doc]);
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
        return view('docs/edit', [
            'doc' => PiaDoc::find($id),
            'collection' => PiaDoc::find($id)->collections->get(0)
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
        $doc = PiaDoc::find($id);
        $doc->content = $request->content;
        $doc->save();

        return redirect()->route('docs.edit', [$doc]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PiaDoc::destroy($id);
        return redirect()->route('docs.index');
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
        if($request->file('image')){
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
                        $request->file('image')->getPathName()
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

            $image->collections()->sync(PiaDoc::find($id)->collections->first());

            $image->save();

            /*$original_file_name = $request->file('image')->getClientOriginalName();
            $file_name = time().'__'.$original_file_name;

            $image->original_file_name = $original_file_name;
            $image->file_name = $file_name;

            $request->file('image')->storeAs(
                'public/uploads', $file_name
            );*/
        }

        return redirect()->route('docs.edit', ['doc' => $id]);
    }
}
