<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Agent;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Date;
use App\Models\DetectionClass;
use App\Models\Image;
use App\Models\Keyword;

class Search extends Component
{
    use WithPagination;

    // get params for direct attributes
    public $query = '';
    public $from = '';
    public $to = '';
    public $coordinates = '';

    // get params for relationships
    public $keyword = '';
    public $detection = '';
    public $agent = '';
    public $place = '';

    // state management
    public $collections;
    public $collection;
    public $selection;
    public $image_ids = '';

    public $cid = '';

    protected $page_size = 48;

    protected $queryString = [
        'query' => ['except' => ''],
        'from' => ['except' => ''],
        'to' => ['except' => ''],
        'coordinates' => ['except' => ''],
        
        'keyword' => ['except' => ''],
        'detection' => ['except' => ''],
        'agent' => ['except' => ''],
        'place' => ['except' => ''],
        
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
        $this->collections = Collection::all()->sortBy('label');
    }

    public function render()
    {
        if($this->query != '' || $this->from != '' || $this->to != '' || $this->coordinates != '' ||
            $this->keyword != '' || $this->agent != '' || $this->place != ''){
            $images = $this->search();
        }

        if($this->keyword != '') {
            /*$keyword_id = $this->keyword; 
            $images = $images->whereHas('keywords', function($q) use($keyword_id) {
                $q->where('id', $keyword_id);
            });*/

            $images = Keyword::find($this->keyword)->images();
        }

        if($this->detection != '') {
            $signatures = [];

            $detections = DetectionClass::find($this->detection)->detections()->get();
            foreach($detections as $key => $detection){
                $signatures[] = $detection->sgv_signature;
            }

            $images = Image::whereIn('signature', $signatures);

            $images->select('images.id', 'images.base_path', 'images.signature', 'images.title');
            $images->orderBy('images.id');
            $images->distinct('images.id');
        }


        if($this->agent != '') {
            /*$agent_id = $this->agent; 
            $images = Image::whereHas('agents', function($q) use($agent_id) {
                $q->where('id', '=', $agent_id);
            });*/

            $images = Agent::find($this->agent)->images();
        }

        if($this->place != '') {
            $images = $images->where('place_id', $this->place)->orderBy('id');
        }

        if(!isset($images)){
            return view('livewire.search', [
                'images' => Image::inRandomOrder()->limit(6)->get(),
                'iotd' => true
            ]);
        } else {
            return view('livewire.search', [
                'images' => $images->paginate($this->page_size),
                'pagination' => true
            ]);
        }
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
        $image_query = Image::with(['comments']);

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
                
            });

            $comment_query = Comment::with([]);
            $comment_query->where(function($q) use ($terms) {
                foreach($terms as $k => $term) {
                    $q->where(DB::raw('lower(comment)'), 'like', '%' . strtolower($term) . '%');
                }
            });
            $ids_via_comments = [];
            $comments = $comment_query->select('image_id')->get();
            
            foreach($comments as $key => $comment) {
                $ids_via_comments[] = $comment->image_id;
            }

            $image_query->orWhereIn('id', $ids_via_comments);

        }

        /**
         * querying relationships:
         * - dates
         */
        if($this->from != '' || $this->to != '') {

            $dates = [$this->from, $this->to];

            $image_query
                ->join('dates', 'dates.id', '=', 'images.date_id')
                ->where(function($q) use ($dates){
                    $q->whereBetween('dates.date', $dates)
                    ->orWhereNotNull('dates.end_date')->whereBetween('dates.end_date', $dates);
                }); 
        }

        /**
         * querying relationships:
         * - coordinates
         */
        if($this->coordinates != '') {
            $_coordinates  = explode(',', $this->coordinates);

            $image_query
                ->join('places', 'places.id', '=', 'images.place_id')
                ->where('latitude', '<=', $_coordinates[0])
                ->where('longitude', '>=', $_coordinates[1])
                ->where('latitude', '>=', $_coordinates[2])
                ->where('longitude', '<=', $_coordinates[3]);
        }

        $image_query->select('images.id', 'images.base_path', 'images.signature', 'images.title');
        $image_query->orderBy('images.id');
        $image_query->distinct('images.id');

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

    public function clear_coordinates() {
        $this->coordinates = '';
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
