<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Call;
use App\Models\Collection;
use App\Models\Keyword;

class CallController extends Controller
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
        $call = Call::create([
            'collection_id' => $request->collection_id,
            'type' => 1
        ]);

        return redirect()->route('calls.edit', [$call]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $call = Call::find($id);
        $call_type = '1';

        if($call->type) {
            $call_type = $call->type;
        }

        return view('calls/show/type-'.$call_type, [
            'call' => $call
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
        return view('calls/edit', [
            'call' => Call::find($id),
            'keywords' => Keyword::all()->sortBy('label')
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
        $call = Call::find($id);

        $call->label = $request->label;
        $call->creator = $request->creator;
        $call->description = $request->description;
        $call->start_date = $request->start_date;
        $call->end_date = $request->end_date;
        $call->keywords()->sync($request->keywords);
        $call->type = $request->type;

        $call->save();

        return redirect()->route('calls.show', [$call]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collection = Call::find($id)->collection;
        Call::destroy($id);

        return redirect()->route('collections.show', [$collection]);
    }
}
