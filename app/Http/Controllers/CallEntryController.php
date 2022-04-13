<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Call;
use App\Models\CallEntry;
use App\Models\Collection;
use App\Models\Keyword;

class CallEntryController extends Controller
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
        return view('callentries/create', [
            'call_id' => $request->call_id,
            'keywords' => Keyword::all()->sortBy('label')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $call_entry = CallEntry::create([
            'label' => $request->label,
            'creator' => $request->creator,
            'comment' => $request->comment,
            'call_id' => $request->call_id
        ]);

        $call_entry->keywords()->sync($request->keywords);

        $call_entry->save();

        if($files = $request->file('documents')){
            foreach($files as $file){

                $label = implode('.', explode('.', $file->getClientOriginalName(), -1));
                $original_file_name = $file->getClientOriginalName();
                $file_name = time().'_'.$original_file_name;
                $base_path = 'documents';

                $document = $call_entry->documents()->create([
                    'label' => $label,
                    'file_name' => $file_name,
                    'original_file_name' => $original_file_name,
                    'base_path' => $base_path,
                ]);

                $file->storeAs(
                    'public/'.$base_path, $file_name
                );
            }
        }

        return redirect()->route('calls.show', [$call_entry->call]);
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
        //
    }
}
