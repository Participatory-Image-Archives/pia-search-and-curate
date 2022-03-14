<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Date;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Search extends Component
{
    public $query = '';
    public $from = '';
    public $to = '';

    public $images;
    public $selection;
    public $image_ids = '';

    protected $queryString = [
        'query' => ['except' => ''],
        'from' => ['except' => ''],
        'to' => ['except' => ''],
    ];

    public function mount()
    {
        $this->images = collect([]);
        $this->selection = collect([]);
    }

    public function render()
    {
        $this->update();
        return view('livewire.search');
    }

    public function update()
    {
        if($this->query != '') {
            $this->images = collect([]);

            $_images = $this->search();

            /**
             * filter by dates
             */
            if($this->from != '' || $this->to != ''){
                if($this->from != '') {
                    $_from = strtotime($this->from);
                } else {
                    $_from = strtotime($this->to);
                }
                if($this->to != '') {
                    $_to = strtotime($this->to);
                } else {
                    $_to = strtotime($this->from);
                }

                $_images = $_images->filter(function($value, $key) use($_from, $_to){
                    if(!$value->dates->count()) return;

                    if($value->dates->first()->date != '') {
                        $_image_from = strtotime($value->dates->first()->date);
                    } else {
                        $_image_from = strtotime($value->dates->first()->end_date);
                    }
                    if($value->dates->first()->end_date != '') {
                        $_image_to = strtotime($value->dates->first()->end_date);
                    } else {
                        $_image_to = strtotime($value->dates->first()->date);
                    }

                    return ($_image_from >= $_from) && ($_image_to <= $_to);
                });
            }

            $this->images = $_images;
        } else {
            if($this->from != '' || $this->to != ''){
                $_images = collect([]);

                if($this->from != '') {
                    $_from = $this->from;
                } else {
                    $_from = $this->to;
                }
                if($this->to != '') {
                    $_to = $this->to;
                } else {
                    $_to = $this->from;
                }
    
                $dates = [$_from, $_to];
                
                $date_query = Date::with([]);

                $date_query->whereBetween('dates.date', $dates);
                $date_query->orWhereNotNull('dates.end_date')->whereBetween('end_date', $dates);

                $dates = $date_query->get();
                
                foreach ($dates as $key => $date) {
                    $_images = $_images->merge($date->images);
                }

                $this->images = $_images;
            }
        }
    }

    protected function search()
    {
        $terms = explode(' ', $this->query);

        /**
         * querying the images direct attributes:
         * - title
         * - signature
         * - old number (oldnr)
         */
        $image_query = Image::with([]);

        foreach($terms as $k => $term) {
            $image_query->where(DB::raw('lower(title)'), 'like', '%' . strtolower($term) . '%');
        }

        $image_query->orWhere(DB::raw('lower(signature)'), 'like', '%' . strtolower($this->query) . '%');
        $image_query->orWhere(DB::raw('lower(oldnr)'), 'like', '%' . strtolower($this->query) . '%');

        $image_query->select('id', 'base_path', 'signature', 'title');

        $images = $image_query->get();

        /**
         * querying comments
         */
        $comment_query = Comment::with([]);

        foreach($terms as $k => $term) {
            $comment_query->where(DB::raw('lower(comments.comment)'), 'like', '%' . strtolower($term) . '%');
        }

        $comments = $comment_query->get();

        foreach ($comments as $key => $comment) {
            $images = $images->merge($comment->images);
        }

        /**
         * 
         */
        /*if($this->from != '' || $this->to != ''){
            if($this->from != '') {
                $_from = $this->from;
            } else {
                $_from = $this->to;
            }
            if($this->to != '') {
                $_to = $this->to;
            } else {
                $_to = $this->from;
            }

            $dates = [$_from, $_to];

            $image_query->whereHas('dates', function($q) use ($dates) {
                $q->whereBetween('dates.date', $dates);
                $q->orWhereNotNull('dates.end_date')->whereBetween('end_date', $dates);
            });
        }*/

        return $images;
    }

    public function select($id) {
        $image = Image::find($id);

        if($this->selection->doesntContain('id', $image->id)){
            $this->selection->push($image);
        }

        $this->set_image_ids();
    }

    public function forget($id) {
        $image = Image::find($id);

        foreach($this->selection as $key => $item) {
            if($item['id'] == $image->id) {
                $this->selection->forget($key);
            }
        }

        $this->set_image_ids();
    }

    public function set_image_ids() {
        $this->image_ids = $this->selection->map(function($item, $key){
            return $item['id'];
        })->join(',');
    }

    public function delete_selection() {
        $this->selection = collect([]);
    }
}
