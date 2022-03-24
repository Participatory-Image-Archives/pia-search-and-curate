@extends('base')

@section('content')
<div class="p-4">

    <div class="md:flex mb-10">
        <div class="md:w-1/2">
            <h2 class="text-2xl mb-2">
                {{ $image->title ?? '' }}
            </h2>
            <div>
                <x-links.cta label="Back" :href="route('images.show', [$image])"/>
                <a class="text-xs mr-1 ml-2 underline" href="{{ route('images.similar', [$image]) }}?category=localcolor">localcolor</a>
                <a class="text-xs mr-1 underline" href="{{ route('images.similar', [$image]) }}?category=globalcolor">globalcolor</a>
                <a class="text-xs mr-1 underline" href="{{ route('images.similar', [$image]) }}?category=localfeatures">localfeatures</a>
            </div>
        </div>
    </div>

    <div class="flex">
        <div class="w-full md:w-1/4 pr-4">
            <h2>Original Image</h2>
            <img class="inline-block mr-2 w-full shadow-2xl" src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/480,/0/default.jpg" alt="{{ $image->title }}" title="{{ $image->title }}">
        </div>
        <div class="w-full md:w-3/4">
            <h2>Top 100 results, sorted by certainity</h2>
            <div class="grid grid-cols-4 gap-2">
                @foreach ($similar as $img)
                    <div>
                        <a href="{{ route('images.show', [$img]) }}">
                            <img class="w-full" src="https://pia-iiif.dhlab.unibas.ch/{{$img->base_path}}/{{$img->signature}}.jp2/full/240,/0/default.jpg"/>
                        </a>
                    </div>
                @endforeach
            </div>
        
        </div>
    </div>

</div>
@endsection