@extends('base')

@section('content')
<div class="p-4">
    <ul>
        @foreach ($collections as $collection)
            @if ($collection->origin != 'salsah')
                <li class="mb-4">
                    <a class="inline-block py-1 px-3 text-xs mr-2 mb-2 rounded-full bg-black text-white" href="/?collection={{ $collection->id }}">{{ $collection->label }}</a>
                    <form action="{{ env('DOCS_URL') }}/create" method="get" class="inline-block">
                        @csrf
                        <input type="hidden" name="collections" value="{{ $collection->id }}">
                        <input type="hidden" name="label" value="{{ $collection->label }}">
                        <button type="submit" class="inline-block py-1 px-3 text-xs mr-2 mb-2 rounded-full border border-gray-500 hover:bg-black">📝</button>
                    </form>
                    <div class="overflow-x-scroll whitespace-nowrap">
                        @foreach ($collection->images as $image)
                            @foreach ($image->collections as $c)
                                @if ($c->origin == 'salsah')
                                    <a href="https://data.dasch.swiss/resources/{{ $image->salsah_id }}" target="_blank">
                                        <img class="inline-block mr-2" src="https://pia-iiif.dhlab.unibas.ch/{{$c->signature}}/{{$image->signature}}.jp2/full/160,/0/default.jpg" alt="">
                                    </a>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </li>
            @endif
        @endforeach
    </ul>
</div>
@endsection