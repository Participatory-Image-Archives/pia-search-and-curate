@extends('base')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />  
@endsection

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex" id="searchable-list" >
        <div class="fixed h-screen w-1/2 overflow-hidden">
            <div id="map" class="map h-full w-full z-40"></div>
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            <div class="pt-14 pb-20 pl-14 pr-4">

                <form action="{{ route('places.update', [$place]) }}" method="post">
                    @csrf
                    @method('patch')

                <div class="relative flex items-center justify-between mb-12 ">
                    <h2 class="text-4xl text-center">
                        <input type="text" name="label" value="{{ $place->label ?? '' }}" class="w-full border border-gray-300 p-1 px-2">
                    </h2>
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
                        <td>
                            <input type="text" name="latitude" value="{{ $place->latitude ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td>Longitude</td>
                        <td>
                            <input type="text" name="longitude" value="{{ $place->longitude ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td>Geonames ID</td>
                        <td>
                            <input type="text" name="geonames_id" value="{{ $place->geonames_id ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td>Geonames Code</td>
                        <td>
                            <input type="text" name="geonames_code" value="{{ $place->geonames_code ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td>Geonames Code Name</td>
                        <td>
                            <input type="text" name="geonames_code_name" value="{{ $place->geonames_code_name ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td>Geonames Division Level</td>
                        <td>
                            <input type="text" name="geonames_division_level" value="{{ $place->geonames_division_level ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td>Wikipedia</td>
                        <td>
                            <input type="text" name="wiki_url" value="{{ $place->wiki_url ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                </table>

                <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-300 bg-white">
                    <button type="submit" class="hover:underline">Save info</button>
                    <span>Search on <a href="https://www.geonames.org/" class="underline" target="_blank">Geonames</a></span>
                </div>

                </form>
            </div>
        </div>
    </div>

    <aside id="sidebar"
        x-data="{expand_collections: false}"
        @mouseleave="expand_collections = false;"
        class="flex fixed top-0 right-0 transform transition min-h-screen shadow-2xl z-50 print-hidden">
        
        <livewire:collections-aside />
    </aside>
</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>  

    <script>

        document.addEventListener('DOMContentLoaded', () => {
            
            let map, marker, center = [{{ $place->latitude ?? '46.818188' }}, {{ $place->longitude ?? '8.227512' }}];

            map = L.map('map', {
                center: center,
                zoom: 12,
                doubleClickZoom: false
            })
            .on('pm:globaldragmodetoggled', e => {
                document.querySelector('input[name="latitude"]').value = marker.getLatLng().lat;
                document.querySelector('input[name="longitude"]').value = marker.getLatLng().lng;
            })

            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox/light-v10',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1IjoidGhnaWV4IiwiYSI6ImNrcjF5Z2ZxZDI2bWYydnFhMWw0eDV2YjIifQ.CZJtOXLy-IZeI6a5ia8Lzw'
            }).addTo(map);

            map.pm.addControls({
                position: 'topleft',
                drawMarker: false,
                drawCircleMarker: false,
                drawPolyline: false,
                drawRectangle: false,
                drawPolygon: false,
                drawCircle: false,
                editMode: false,
                cutPolygon: false,
                rotateMode: false,
                removalMode: false
            });

            marker = new L.Marker(center).addTo(map);

        });

    </script>
@endsection
