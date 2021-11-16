@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-4">
        <h2 class="text-2xl mb-2 md:w-1/2">
            {{ $collection->label }}
        </h2>
        <div class="md:w-1/2 md:text-right">
            <form action="{{ route('collections.destroy', [$collection]) }}" method="post" class="inline-block">
                @csrf
                @method('delete')
                <button type="submit" class="inline-block py-1 px-3 text-xs ml-2 mb-2 rounded-full border border-red-500 text-red-500 hover:bg-red-500 hover:text-white">Delete</button>
            </form>
            <a class="inline-block py-1 px-3 text-xs ml-2 mb-2 rounded-full border border-black hover:bg-black hover:text-white" href="/?collection={{ $collection->id }}">
                Edit Collection    
            </a>
            <a class="inline-block py-1 px-3 text-xs ml-2 mb-2 rounded-full bg-black text-white" href="{{ route('collections.index') }}">
                Collections
            </a>
            <a href="{{ route('keywords.index') }}"
                class="ml-2 inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white">Keywords</a>
            <a class="inline-block py-1 text-xs ml-2 mb-2 underline" href="/">
                ///
            </a>
        </div>
    </div>
    <div class="mb-2">
        <h2 class="text-xs mb-1">Documents</h2>
        <div>
            @foreach ($collection->docs as $doc)
                <a class="inline-block py-1 px-3 text-xs mr-2 mb-2 rounded-full bg-black text-white" href="{{ env('DOCS_URL') }}/{{ $doc->id }}/edit">
                    {{ $doc->label }}  
                </a>
            @endforeach

            <form action="{{ env('DOCS_URL') }}/create" method="get" class="inline-block">
                @csrf
                <input type="hidden" name="collections" value="{{ $collection->id }}">
                <input type="hidden" name="label" value="{{ $collection->label }}">
                <button type="submit" class="inline-block py-1 px-3 text-xs mr-2 mb-2 rounded-full border border-black hover:bg-black hover:text-white">+ New Document</button>
            </form>
        </div>
    </div>
    <h2 class="text-xs mb-1">Images</h2>
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