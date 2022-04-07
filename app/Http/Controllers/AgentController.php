<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agent;
use App\Models\Date;
use App\Models\Place;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('agents/index', [
            'agents' => Agent::orderBy('name')->get()
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
        return view('agents/show', [
            'agent' => Agent::find($id),
            'image_count' => Agent::find($id)->images()->count()
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
        return view('agents/edit', [
            'agent' => Agent::find($id),
            'places' => Place::whereIn('origin', ['salsah', 'pia'])->get()
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
        $agent = Agent::find($id);

        $agent->title = $request->title;
        $agent->name = $request->name;
        $agent->family = $request->family;
        $agent->description = $request->description;
        $agent->birthplace_id = $request->birthplace_id;
        $agent->deathplace_id = $request->deathplace_id;
        $agent->gnd_uri = $request->gnd_uri;

        if($request->append_birthplace != '') {
            $place = Place::create([
                'label' => $request->append_birthplace,
                'origin' => 'pia'
            ]);
            $agent->birthplace_id = $place->id;
        }

        if($request->append_deathplace != '') {
            $place = Place::create([
                'label' => $request->append_deathplace,
                'origin' => 'pia'
            ]);
            $agent->deathplace_id = $place->id;
        }

        if($request->append_birthdate != '') {
            $date = Date::create([
                'date' => $request->append_birthdate
            ]);
            $agent->birthdate_id = $date->id;
        }

        if($request->append_deathdate != '') {
            $date = Date::create([
                'date' => $request->append_deathdate
            ]);
            $agent->deathdate_id = $date->id;
        }

        if($request->remove_birthdate != '') {
            $agent->birthdate->delete();
        }
        if($request->remove_deathdate != '') {
            $agent->deathdate->delete();
        }

        $agent->save();

        return redirect()->route('agents.show', [$id]);
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
