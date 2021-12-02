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
}
