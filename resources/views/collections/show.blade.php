@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-10">
        <div class="md:w-1/2">
            <h2 class="text-2xl mb-2">
                {{ $collection->label }}
            </h2>
            <div>
                <x-links.cta label="Edit" href="/?collection={{ $collection->id }}"/>

                <form action="{{ route('collections.destroy', [$collection]) }}" method="post" class="inline-block">
                    @csrf
                    @method('delete')

                    <x-buttons.delete/>
                </form>

                <x-links.bare label="JSON" href="{{ env('API_URL') }}collections/{{ $collection->id }}" target="_blank"/>
                <x-links.bare label="CSV" :href="route('collections.export', ['id' => $collection->id])"/>
            </div>
        </div>
        <div class="md:w-1/2 md:text-right">
            <x-links.default label="Collections" :href="route('collections.index')"/>
            <x-links.default label="Keywords" :href="route('keywords.index')"/>
            <x-links.default label="Home" href="/"/>
        </div>
    </div>
    <div class="flex mb-10">
        <div class="w-1/2">
            <h2 class="text-xs mb-1">Documents</h2>
            <div>
                @foreach ($collection->docs as $doc)
                    <x-links.default :label="$doc->label" href="{{ env('DOCS_URL') }}/{{ $doc->id }}/edit"/>
                @endforeach

                <form action="{{ env('DOCS_URL') }}/create" method="get" class="inline-block">
                    @csrf
                    <input type="hidden" name="collections" value="{{ $collection->id }}">
                    <input type="hidden" name="label" value="{{ $collection->label }}">
                    
                    <x-buttons.default label="New Document" type="submit"/>
                </form>
            </div>
        </div>
        <div class="w-1/2">
            <h2 class="text-xs mb-1">Maps</h2>
            <div>
                @foreach ($collection->maps as $map)
                    <x-links.default :label="$map->label" href="{{ env('MAPS_URL') }}/{{ $map->id }}/edit"/>
                @endforeach

                <form action="{{ env('MAPS_URL') }}/create" method="get" class="inline-block">
                    @csrf
                    <input type="hidden" name="collections" value="{{ $collection->id }}">
                    <input type="hidden" name="label" value="{{ $collection->label }}">
                    
                    <x-buttons.default label="New Map" type="submit"/>
                </form>
            </div>
        </div>
    </div>
    <h2 class="text-xs mb-1">Images</h2>
    <div class="grid gap-4 grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
        @foreach ($collection->images as $image)
            <a href="{{ route('images.show', [$image]) }}">
                <img class="inline-block mr-2 w-full" src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/320,/0/default.jpg" alt="{{$image->title}}" title="{{$image->title}}">
            </a>
        @endforeach
    </div>
</div>
@endsection