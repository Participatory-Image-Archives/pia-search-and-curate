@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-4">
        <div class="md:w-1/2">
            <h2 class="text-2xl mb-2">
                {{ $collection->label }}
            </h2>
            <div>
                <x-links.cta label="Back" :href="route('collections.show', [$collection])"/>
            </div>
        </div>
        
    </div>
    <div class="w-full mb-10">
        {!! $collection->description !!}
    </div>
    <form action="{{ route('collections.doCopy', [$collection]) }}" method="POST" class="flex">
        @csrf

        <div class="w-1/4 pr-4">
            <p class="text-xs mb-2">Choose the collection to which you want to copy the images you selected on the right. Leave empty and enter a label to start a new collection.</p>

                <label for="collection_id">Collection…</label>
                <select name="collection_id" id="collection_id" class="w-full text-xs px-2 bg-black text-white rounded-full" style="height: 26px;">
                    <option value="">-</option>
                    @foreach ($collections as $c)
                        @if($c->origin != 'salsah')
                            <option value="{{ $c->id }}">{{ $c->label }}</option>
                        @endif
                    @endforeach
                </select>
                <label for="collection_label" class="mt-2 inline-block">… or collection label</label>
                <input type="text" name="collection_label" class="w-full text-xs px-2 border border-black rounded-full mb-4" style="height: 26px;" paceholder="Collection label">
                <button type="submit">Copy</button>
        </div>
        <div class="w-3/4">
            <div class="grid gap-4 grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
                @foreach ($collection->images as $image)
                    <div class="relative">
                        <img class="inline-block mb-2 w-full" src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/320,/0/default.jpg" alt="{{$image->title}}" title="{{$image->title}}">
                        <input type="checkbox" name="image_{{ $image->id }}" class="absolute top-2 right-2 cursor-pointer" style="width: 30px; height: 30px">
                    </div>
                @endforeach
            </div>
        </div>

    </form>
</div>
@endsection