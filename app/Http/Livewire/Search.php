<?php

namespace App\Http\Livewire;

use App\Models\Collection;
use App\Models\Comment;
use App\Models\Date;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $query = '';
    public $from = '';
    public $to = '';
    public $cid = '';

    public $collection;
    public $selection;
    public $image_ids = '';

    protected $queryString = [
        'query' => ['except' => ''],
        'from' => ['except' => ''],
        'to' => ['except' => ''],
        'cid' => ['except' => ''],
    ];

    public function mount()
    {
        if($this->cid != ''){
            $this->collection = Collection::find($this->cid);
            $this->selection = $this->collection->images;
        } else {
            $this->selection = collect([]);
        }
    }

    public function render()
    {
        $image_query = $this->search();

        if($this->query != '' || $this->from != '' || $this->to != '') {
            $images = $image_query->paginate(48);
        } else {
            $images = DB::connection('pia')->table('images')
                ->inRandomOrder()
                ->paginate(48);
        }

        return view('livewire.search', [
            'images' => $images
        ]);
    }

    public function update()
    {
        if($this->from != '' || $this->to != ''){
            if($this->from == '') {
                $this->from = $this->to;
            }
            if($this->to == '') {
                $this->to = $this->from;
            }
        }

        $this->resetPage();
    }

    protected function search()
    {
        $image_query = Image::with([]);

        if($this->query != ''){
            $terms = explode(' ', $this->query);

            $image_query->where(function($q) use ($terms) {
                /**
                 * querying the images direct attributes:
                 * - title
                 * - signature
                 * - old number (oldnr)
                 */
                foreach($terms as $k => $term) {
                    $q->where(DB::raw('lower(title)'), 'like', '%' . strtolower($term) . '%');
                }
    
                $q->orWhere(DB::raw('lower(oldnr)'), 'like', '%' . strtolower($this->query) . '%');
                $q->orWhere(DB::raw('lower(signature)'), 'like', '%' . strtolower($this->query) . '%');

                // TODO: Optimize via joins
                $q->orWhereHas('comments', function($q) use ($terms) {
                    foreach($terms as $k => $term) {
                        $q->where(DB::raw('lower(comment)'), 'like', '%' . strtolower($term) . '%');
                    }
                });
            });


            /**
             * TODO: make this work
             * querying relationships:
             * - comments
             */
            /*$image_query
                ->join('image_comment', 'images.id', '=', 'image_comment.image_id')
                ->join('comments', 'comments.id', '=', 'image_comment.comment_id')
                ->orWhere(function($q) use ($terms){
                    foreach($terms as $k => $term) {
                        $q->orWhere(DB::raw('lower(comment)'), 'like', '%' . strtolower($term) . '%');
                    }
                });*/
        }

        /**
         * querying relationships:
         * - dates
         */
        if($this->from != '' || $this->to != '') {

            $dates = [$this->from, $this->to];

            $image_query
                ->join('image_date', 'images.id', '=', 'image_date.image_id')
                ->join('dates', 'dates.id', '=', 'image_date.date_id')
                ->where(function($q) use ($dates){
                    $q->whereBetween('dates.date', $dates)
                    ->orWhereNotNull('dates.end_date')->whereBetween('dates.end_date', $dates);
                });
        }

        $image_query->select('images.id', 'images.base_path', 'images.signature', 'images.title');
        $image_query->orderBy('images.id');

        // print(vsprintf(str_replace(array('?'), array('\'%s\''), $image_query->toSql()), $image_query->getBindings()));

        return $image_query;
    }

    public function add_all_results(){
        $this->selection = $this->selection->merge($this->search()->get());
        $this->set_image_ids();
    }

    public function clear_dates() {
        $this->from = '';
        $this->to = '';
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
