<?php

namespace App\Http\Livewire;

use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Search extends Component
{
    public $query = '';
    public $images;
    public $selection;
    public $image_ids = '';

    protected $queryString = [
        'query' => ['except' => '']
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
            $this->images = $this->search();
        }
    }

    protected function search()
    {
        $terms = explode(' ', $this->query);

        $image_query = Image::with(['comments']);

        foreach($terms as $k => $term) {
            $image_query->where(DB::raw('lower(images.title)'), 'like', '%' . strtolower($term) . '%');
        }

        $image_query->orWhere(DB::raw('lower(signature)'), 'like', '%' . strtolower($this->query) . '%');
        $image_query->orWhere(DB::raw('lower(oldnr)'), 'like', '%' . strtolower($this->query) . '%');
        $image_query->orWhere(DB::raw('lower(sequence_number)'), 'like', '%' . strtolower($this->query) . '%');

        $image_query->select('images.id', 'images.base_path', 'images.signature', 'images.title');

        $image_query->orderBy('id', 'desc');

        return $image_query->get();
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
