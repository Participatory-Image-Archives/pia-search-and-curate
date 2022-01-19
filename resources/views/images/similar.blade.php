@extends('base')

@section('content')
<div class="p-4">

    <div class="md:flex mb-10">
        <div class="md:w-1/2">
            <h2 class="text-2xl mb-2">
                {{ $image->title }}
            </h2>
            <div>
                <x-links.cta label="Back" :href="route('images.show', [$image])"/>
            </div>
        </div>
        <div class="md:w-1/2 md:text-right">
            @include('partials.lists-dropdown')
            <x-links.default label="Home" href="/"/>
        </div>
    </div>

    <div class="flex">
        <div class="w-full md:w-1/4 pr-2">
            <img class="inline-block mr-2 w-full shadow-2xl" src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/640,/0/default.jpg" alt="{{ $image->title }}" title="{{ $image->title }}">
        </div>
        <div class="w-full md:w-3/4">

            <div class="grid grid-cols-4 gap-2">
                @foreach ($similar as $img)
                    <div>
                        <a href="{{ route('images.show', [$img]) }}">
                            <img src="https://pia-iiif.dhlab.unibas.ch/{{$img->base_path}}/{{$img->signature}}.jp2/full/240,/0/default.jpg"/>
                        </a>
                    </div>
                @endforeach
            </div>
        
        </div>
    </div>

</div>
@endsection