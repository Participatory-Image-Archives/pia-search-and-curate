@extends('base')

@section('content')
<div class="p-4">
    <div class="mb-4">
        <h2 class="text-2xl mb-2">{{ $collection->label }}</h2>
        <div class="flex justify-between">
            <div class="flex">
                <a class="inline-block py-1 px-3 text-xs mr-2 mb-2 rounded-full bg-black text-white" href="/?collection={{ $collection->id }}">
                    Edit Collection    
                </a>
                <a class="inline-block py-1 px-3 text-xs mr-2 mb-2 underline" href="{{ route('collections.index') }}">
                    View all
                </a>
            </div>
            <form action="{{ route('collections.destroy', [$collection]) }}" method="post">
                @csrf
                @method('delete')
                <button type="submit" class="inline-block py-1 px-3 text-xs mr-2 mb-2 rounded-full border border-red-500 text-red-500 hover:bg-red-500 hover:text-white">Delete</button>
            </form>
        </div>
    </div>
    <div class="grid gap-4 grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
        @foreach ($collection->images as $image)
            @foreach ($image->collections as $c)
                @if ($c->origin == 'salsah')
                    <a href="{{ route('images.show', [$image]) }}">
                        <img class="inline-block mr-2 w-full" src="https://pia-iiif.dhlab.unibas.ch/{{$c->signature}}/{{$image->signature}}.jp2/full/320,/0/default.jpg" alt="{{$image->title}}" title="{{$image->title}}">
                    </a>
                @endif
            @endforeach
        @endforeach
    </div>
</div>
@endsection