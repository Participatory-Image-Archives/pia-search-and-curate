@extends('base')

@section('content')
<div class="p-4 pb-20">
    <div class="md:flex mb-4">
        <h2 class="text-2xl mb-2 md:w-1/2">
            Keyword <strong>{{ $keyword->label }}</strong>
        </h2>
    </div>

    @if($keyword->collections->count())
    <div class="mb-8">
        <h2 class="text-sm font-bold mb-2">{{ $keyword->collections->count() }} Collections</h2>
        <ul>
            @foreach($keyword->collections as $collection)
            <li class="inline">
                <x-links.default :label="$collection->label" :href="route('collections.show', [$collection])" class="mb-2 name"/>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($keyword->calls->count())
    <div class="mb-8">
        <h2 class="text-sm font-bold mb-2">{{ $keyword->calls->count() }} Calls</h2>
        <ul>
            @foreach($keyword->calls as $call)
            <li class="inline">
                <x-links.default :label="$call->label" :href="route('calls.show', [$call])" class="mb-2 name"/>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($keyword->images->count())
    <div class="mb-4">
        <h2 class="text-sm font-bold mb-2">{{ $keyword->images->count() }} Images</h2>
        <p class="text-sm">This keyword has <x-links.default href="/?keyword={{ $keyword->id }}" label="{{ $keyword->images->count() }} images" /> associated. Preview of the first up to 12 images...</p>
        <div class="my-4 list grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-6 gap-4 grid-flow-row">
        @foreach($keyword->images as $image)
            @if($loop->index > 11)
                @break
            @endif
            <a href="{{ route('images.show', [$image]) }}">
                <img class="inline-block mr-2 w-full"
                    src="https://sipi.participatory-archives.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/360,/0/default.jpg"
                    alt="{{$image->title}}" title="{{$image->title}}">
            </a>
        @endforeach
        </div>
        @if($keyword->images->count() > 12)
        <p class="mt-4">
            <a class="py-2 px-4 text-lg bg-black rounded-full text-white" href="/?keyword={{ $keyword->id }}">Show all images</a>
        </p>
        @endif
    </div>
    @endif
</div>
@endsection

