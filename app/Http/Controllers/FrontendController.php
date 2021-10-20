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
    /*
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchImages(Request $request)
    {
        $terms = explode(' ', $request->input('q'));
        $image_query = Image::with([]);
        $comment_query = Comment::with(['images']);
        $image_ids = [];
    
        foreach($terms as $k => $term) {
            $image_query->where(DB::raw('lower(images.title)'), 'like', '%' . strtolower($term) . '%');
            $comment_query->where(DB::raw('lower(comments.comment)'), 'like', '%' . strtolower($term) . '%');
        }
    
        $images = $image_query->get();
        $comments = $comment_query->get();
    
        foreach($images as $k => $image) {
            if(!in_array($image->id, $image_ids)) {
                $image_ids[] = $image->id;
            }
        }
        foreach($comments as $k => $comment) {
            foreach($comment->images as $k => $image) {
                if(!in_array($image->id, $image_ids)) {
                    $image_ids[] = $image->id;
                }
            }
        }
    
        $images = Image::with(['keywords:id,label', 'collections:signature,origin'])
                    ->whereIn('id', $image_ids)
                    ->select('images.id', 'images.salsah_id', 'images.signature', 'images.title')
                    ->get();
    
        return response()->json($images);
    }

    /*
     * Grab all images via their ids
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getImagesByIds(Request $request)
    {
        $image_ids = explode(',', $request->input('ids'));
    
        $images = Image::with(['collections:signature,origin', 'location'])
                    ->whereIn('id', $image_ids)
                    ->select('images.id', 'images.salsah_id', 'images.signature', 'images.title')
                    ->get();
    
        return response()->json($images);
    }

    /*
     * Grab all images via their ids
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCollection(Request $request)
    {
        $collection = Collection::find($request->input('c'));
    
        return response()->json([
            'id' => $collection->id,
            'label' => $collection->label,
            'image_ids' => $collection->images_ids()->join(',')
        ]);
    }
}

