@extends('base')

@section('content')
<div class="p-4">
    <ul>
        @foreach ($collections as $collection)
            <li>
                <a class="block font-bold hover:underline" href="/?c={{ $collection->id }}">{{ $collection->label }}</a>
                @foreach ($collection->images as $image)
                    @foreach ($image->collections as $c)
                        @if ($c->origin == 'salsah')
                            <a href="https://data.dasch.swiss/resources/{{ $image->salsah_id }}" target="_blank">
                                <img class="inline-block mr-2" src="https://pia-iiif.dhlab.unibas.ch/{{$c->signature}}/{{$image->signature}}.jp2/full/160,/0/default.jpg" alt="">
                            </a>
                        @endif
                    @endforeach
                @endforeach
            </li>
        @endforeach
    </ul>
</div>
@endsection