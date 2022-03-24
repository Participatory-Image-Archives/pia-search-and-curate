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
        $doc->label = $request->label;
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
        $collection = PiaDoc::find($id)->collections->get(0);
        PiaDoc::destroy($id);
        return redirect()->route('collections.show', [$collection]);
    }
}
