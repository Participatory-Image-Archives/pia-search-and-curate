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
    <div class="flex">
        <div class="w-1/4 pr-4">
            <p class="text-xs mb-2">These images do not have a location attached.</p>
            @foreach ($collection->images as $image)
                @if (!$image->place)
                <a href="{{ route('images.show', [$image]) }}">
                    <img class="inline-block mb-2 w-full" src="https://sipi.participatory-archives.chh/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/320,/0/default.jpg" alt="{{$image->title}}" title="{{$image->title}}">
                </a>
                @endif
            @endforeach
        </div>
        <div class="w-3/4">
            @include('collections.image-actions')
            <div id="map" class="w-full" style="height: 800px"></div>
        </div>
    </div>
</div>

@endsection