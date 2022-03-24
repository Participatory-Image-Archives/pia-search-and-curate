@extends('base')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
@endsection

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex" id="searchable-list" >
        <div class="fixed h-screen w-1/2 overflow-hidden">
            <div id="map" class="map h-full w-full z-40"></div>
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            <div class="pt-14 pb-20 pl-14 pr-4">
                <div class="relative flex items-center justify-between mb-12 ">
                    <h2 class="text-4xl text-center">
                        {{ $location->label }}
                    </h2>
                </div>

                <div class="mb-10">
                    <span class="text-xs">View </span>
                    <x-links.bare label="API JSON" href="{{ env('API_URL') }}locations/{{ $location->id }}" target="_blank"/>, 
                    <x-links.bare label="All related images ({{ $image_count }})" href="/?location={{ $location->id }}"/>
                </div>

                <table class="w-full">
                    <thead class="text-xs">
                        <tr>
                            <td class="pb-2 w-1/3">Field</td>
                            <td class="pb-2">Value</td>
                        </tr>
                    </thead>
                    <tr>
                        <td>Latitude</td>
                        <td>{{ $location->latitude ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Longitude</td>
                        <td>{{ $location->longitude ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Geonames ID</td>
                        <td>{{ $location->geonames_id ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Geonames URL</td>
                        <td>
                            @if ($location->geonames_uri != '')
                                <a href="{{ $location->geonames_uri }}" class="underline" target="_blank">{{ $location->geonames_uri }}</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Geonames Code</td>
                        <td>{{ $location->geonames_code ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Geonames Code Name</td>
                        <td>{{ $location->geonames_code_name ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Geonames Division Level</td>
                        <td>{{ $location->geonames_division_level ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><p class="text-xs my-2">The division level shows how deep this entry is in the administrative division. The exactitude of this information can change from country to country, is crowdsourced, and is not formative or set by any standards.</p></td>
                    </tr>
                    <tr>
                        <td>Wikipedia</td>
                        <td>
                            @if ($location->wiki_url != '')
                                <a href="{{ $location->wiki_url }}" class="underline" target="_blank">{{ $location->wiki_url }}</a>
                            @else
                                –
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-700 bg-white">
                    <a class="hover:underline" href="{{ route('locations.edit', [$location]) }}">Edit info</a>
                    <form action="{{ route('locations.destroy', [$location]) }}" method="post" class="inline-block">
                        @csrf
                        @method('delete')
    
                        <x-buttons.delete/>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <aside id="sidebar"
        x-data="{expand_collections: false}"
        @mouseleave="expand_collections = false;"
        class="flex fixed top-0 right-0 transform transition min-h-screen shadow-2xl z-50 print-hidden">
        
        @include('frontend.partials.aside-collections')
    </aside>
</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <script>

        document.addEventListener('DOMContentLoaded', () => {
            
            let map, marker, center = [{{ $location->latitude ?? '46.818188' }}, {{ $location->longitude ?? '8.227512' }}];

            map = L.map('map', {
                center: center,
                zoom: 12,
                doubleClickZoom: false
            })

            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox/light-v10',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1IjoidGhnaWV4IiwiYSI6ImNrcjF5Z2ZxZDI2bWYydnFhMWw0eDV2YjIifQ.CZJtOXLy-IZeI6a5ia8Lzw'
            }).addTo(map);

            marker = new L.Marker(center).addTo(map);

            @if ($location->geometry)
                let outline = L.geoJSON({
                    "type": "Feature",
                    "geometry": {!! $location->geometry !!}
                }).addTo(map);

                map.fitBounds(outline.getBounds());
            @endif

        });

    </script>
@endsection
