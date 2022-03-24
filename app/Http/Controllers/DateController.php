<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Date;
use App\Models\Image;

class DateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images_w_acc_1 = Image::whereHas('dates', function($q){
            $q->where('accuracy', '1');
        })->count();
        $images_w_acc_2 = Image::whereHas('dates', function($q){
            $q->where('accuracy', '2');
        })->count();
        $images_w_acc_3 = Image::whereHas('dates', function($q){
            $q->where('accuracy', '3');
        })->count();
        $images_w_date_range = Image::whereHas('dates', function($q){
            $q->whereNotNull('end_date');
        })->count();

        return view('dates/index', [
            'images' => Image::count(),
            'images_wo' => Image::doesnthave('dates')->count(),
            'images_w_acc_1' => $images_w_acc_1,
            'images_w_acc_2' => $images_w_acc_2,
            'images_w_acc_3' => $images_w_acc_3,
            'images_w_date_range' => $images_w_date_range
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
