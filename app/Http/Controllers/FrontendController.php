<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Image;
use App\Models\Keyword;
use App\Models\Collection;
use App\Models\Comment;

class FrontendController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exportCollection($id)
    {
        $collection = Collection::find($id);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
    
        $columns = array(
            'salsah_id', 'oldnr', 'signature', 'title', 'file_name', 'sequence_number',
            'object_type', 'model_type', 'format',
            'keywords','collections','comments','people','dates','location',
        );
    
        $callback = function() use ($collection, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach($collection->images as $image) {

                $data = [
                    $image->salsah_id,
                    $image->oldnr,
                    $image->signature,
                    $image->original_title,
                    $image->original_file_name,
                    $image->sequence_number,

                    '', '', '', 
                    '', '', '', '', '', '',
                ];

                if($image->objectType) {
                    $data[6] = $image->objectType->label .'/'. $image->objectType->comment;
                }
                if($image->modelType) {
                    $data[7] = $image->modelType->label .'/'. $image->modelType->comment;
                }
                if($image->format) {
                    $data[8] = $image->format->label .'/'. $image->format->comment;
                }

                if($image->keywords) {
                    foreach($image->keywords as $keyword) {
                        $data['9'] .= $keyword->label . ', ';
                    }
                }
                if($image->collections) {
                    foreach($image->collections as $collection) {
                        $data['10'] .= $collection->label . ', ';
                    }
                }
                if($image->comments) {
                    foreach($image->comments as $comment) {
                        $data['11'] .= $comment->comment . ', ';
                    }
                }
                if($image->people) {
                    foreach($image->people as $person) {
                        $data['12'] .= $person->name . ', ';
                    }
                }
                if($image->location) {
                    $data['13'] = $image->location->label;
                }

                $data['9'] = rtrim($data['9'], ', ');
                $data['10'] = rtrim($data['10'], ', ');
                $data['11'] = rtrim($data['11'], ', ');
                $data['12'] = rtrim($data['12'], ', ');

                fputcsv($file, $data);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, $collection->label.'.csv');
    }
}

