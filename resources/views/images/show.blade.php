@extends('base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('node_modules/leaflet/dist/leaflet.css') }}">
@endsection

@section('content')
<div class="p-4">

    <div class="md:flex mb-10">
        <div class="md:w-1/2">
            <h2 class="text-2xl mb-2">
                {{ $image->title }}
            </h2>
            <div>
                <x-links.cta label="Edit" :href="route('images.edit', [$image])"/>
                {{--<form action="{{ route('images.destroy', [$image]) }}" method="post" class="inline-block mr-2">
                    @csrf
                    @method('delete')

                    <x-buttons.delete/>
                </form>--}}
                <x-links.bare label="IIIF Image API" href="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/max/0/default.jpg" target="_blank"/>
                <x-links.bare label="info.json" href="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/info.json" target="_blank"/>
                <x-links.bare label="API JSON" href="{{ env('API_URL') }}images/{{ $image->id }}" target="_blank"/>
                <x-links.bare label="SALSAH" href="https://data.dasch.swiss/resources/{{ $image->salsah_id }}" target="_blank"/>
                <span class="text-xs">{{ $_SERVER['REMOTE_ADDR'] }}</span>
            </div>
        </div>
        <div class="md:w-1/2 md:text-right">
            @include('partials.lists-dropdown')
            <x-links.default label="Home" href="/"/>
        </div>
    </div>

    <div class="flex">
        <div class="w-full md:w-1/2">
            {{--<img class="inline-block mr-2 w-full shadow-2xl" src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/640,/0/default.jpg" alt="{{ $image->title }}" title="{{ $image->title }}">--}}
            <div id="iiif-image" class="min-h-full"></div>
        </div>
        <div class="w-full md:w-1/2">

            <div class="py-4 px-6">

                <table class="w-full">
                    <thead class="text-xs">
                        <tr>
                            <td class="pb-2 w-1/3">Field</td>
                            <td class="pb-2">Value</td>
                        </tr>
                    </thead>
                    <tr>
                        <td>SALSAH ID</td>
                        <td>{{ $image->salsah_id ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Old Nr</td>
                        <td>{{ $image->oldnr ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Signature</td>
                        <td>{{ $image->signature ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Original Title</td>
                        <td>{{ $image->original_title ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Sequence Number</td>
                        <td>{{ $image->sequence_number ?? '–' }}</td>
                    </tr>
                </table>

                <hr class="my-4">

                <table class="w-full">
                    <tr>
                        <td class="w-1/3">Verso</td>
                        <td>
                            @if ($image->verso)
                                <a href="{{ route('images.show', [$id => $image->verso]) }}">Verso</a>
                            @else
                            –
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Object Type</td>
                        <td>
                            {{ $image->objectType->label ?? '–' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Model Type</td>
                        <td>
                            {{ $image->modelType->label ?? '–' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Format</td>
                        <td>
                            {{ $image->format->label ?? '–' }} / 
                            {{ $image->format->comment ?? '–' }}
                        </td>
                    </tr>
                </table>
                
                <hr class="my-4">

                <div>
                    <h3 class="mb-1 text-xs">Keywords</h3>
                    <div class="mb-2">
                        @forelse ($image->keywords as $keyword)
                            @if ($keyword->label)
                                <x-links.default href="/?keyword={{ $keyword->id }}" :label="$keyword->label" class="mb-2"/>
                            @endif
                        @empty
                        –
                        @endforelse
                    </div>
                    <h3 class="mb-1 text-xs">Collections</h3>
                    <div class="mb-2">
                        @forelse ($image->collections as $collection)
                            @if ($collection->label)
                                <x-links.default :href="route('collections.show', [$collection])" :label="$collection->label" class="mb-2"/>
                            @endif
                        @empty
                        –
                        @endforelse
                    </div>
                </div>
                
                <hr class="my-4">

                <div>
                    <h3 class="mb-1 text-xs">Comments</h3>
                    @forelse ($image->comments as $comment)
                        @if ($comment->comment)
                            <p class="mb-2 text-sm">– {{ $comment->comment }}</p>
                        @endif
                    @empty
                    -
                    @endforelse
                </div>
                
                <hr class="my-4">

                <div>
                    <h3 class="mb-1 text-xs">People</h3>
                    <div class="mb-2">
                        @forelse ($image->people as $person)
                            @if ($person->name)
                                <x-links.default href="/?person={{ $person->id }}" :label="$person->name" class="mb-2"/>
                            @endif
                        @empty
                        –
                        @endforelse
                    </div>
                    <h3 class="mb-1 text-xs">Dates</h3>
                    <div class="flex mb-2">
                        @forelse ($image->dates as $date)
                            @if ($date->date)
                                <span class="inline-block py-1 px-3 text-xs rounded-full bg-black text-white mr-2 mb-2">{{ date('d. M Y', strtotime($date->date)); }}</span>
                            @endif
                            @if ($date->date_string)
                                <span class="inline-block py-1 px-3 text-xs underline mr-2 mb-2">{{ $date->date_string }}</span>
                            @endif
                        @empty
                        –
                        @endforelse
                    </div>
                    <h3 class="mb-1 text-xs">Locations</h3>
                    <div class="flex mb-2">
                        @if ($image->location)
                            @if ($image->location->label)
                                <span class="inline-block py-1 px-3 text-xs rounded-full bg-black text-white mr-2 mb-2">{{ $image->location->label }}</span>
                            @endif
                        @else
                        -
                        @endforelse
                    </div>
                </div>
            </div>
        
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('node_modules/leaflet/dist/leaflet.js') }}"></script>
    <script src="{{ asset('node_modules/leaflet-iiif/leaflet-iiif.js') }}"></script>

    <script>

        document.addEventListener('DOMContentLoaded', () => {
            var image = L.map('iiif-image', {
                center: [0, 0],
                crs: L.CRS.Simple,
                zoom: 0,
            });

            L.tileLayer.iiif('https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/info.json').addTo(image);
        })

    </script>
@endsection
