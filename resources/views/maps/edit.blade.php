@extends('base')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />  
@endsection

@section('content')
<div class="flex max-h-screen min-h-screen" x-data="app">
    <div class="bg-black p-4 pt-14 max-h-screen overflow-scroll transition-all"
        :class="minimize_collection ? 'w-auto' : 'w-1/4'">
        <div class="grid grid-cols-1 gap-4" id="images">
            @foreach ($collection->images as $image)
            <img class="inline-block mr-2 w-full"
                id="{{ $image->id }}"
                src="https://sipi.participatory-archives.chh/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/360,/0/default.jpg"
                alt="{{$image->title}}" title="{{$image->title}}" @dragstart="dragstart">
            @endforeach
        </div>
    </div>

    <div class="relative overflow-y-auto bg-gray-100 transition-all" :class="minimize_collection ? 'w-12' : 'w-1/2'">
        <button class="absolute top-2 right-2" @click="minimize_collection = ! minimize_collection; setTimeout(function(){map.invalidateSize(true);},250);">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div x-show="! minimize_collection">
            @include('collections.partials.collection-info')
        </div>
        <div x-show="minimize_collection">
            <h2 class="text-4xl mt-14 ml-1 whitespace-nowrap" style="text-orientation: upright; writing-mode: vertical-rl;">
                {{ $collection->label }}</h2>
        </div>
    </div>

    <div class="h-screen overflow-hidden transition-all" :class="minimize_collection ? 'w-2/3' : 'w-1/4'">
        <div id="map" class="map h-full w-full z-40"
            @drop="drop" @dragover="dragover"></div>
        
        <div class="flex justify-between absolute bottom-0 px-8 py-2 border-t leading-10 border-gray-700 bg-white z-50"
            :class="minimize_collection ? 'w-2/3' : 'w-1/4'">
            <form action="{{ route('maps.imagesUpdate', [$map]) }}" method="post" x-ref="markerform">
                @csrf
                @method('patch')

                <input type="hidden" name="markerdata" x-ref="markerdata">
                <button type="button" class="hover:underline" @click="update_and_submit()">Save</button>
            </form>

            <form action="{{ route('maps.destroy', [$map]) }}" method="post" class="inline-block">
                @csrf
                @method('delete')

                <x-buttons.delete/>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>  
<style>

    .leaflet-popup-content-wrapper {
        padding: 0;
        border-radius: 0;
    }
    .leaflet-popup-content {
        margin: 0;
        max-width: 320px;
    }

</style>
<script>

    let map;

    let custom_entry = L.Marker.extend({
        imageid: 0,
        alt: ''
    });

    document.addEventListener('DOMContentLoaded', () => {
        map = L.map('map', {
            center: [46.818188, 8.227512],
            zoom: 8,
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
        });

        @foreach ($map->mapEntries as $entry)
            @if ($entry->place)
                let entry_{{ $entry->id }} = new custom_entry([{{$entry->place->latitude}}, {{$entry->place->longitude}}]).addTo(map);

                entry_{{ $entry->id }}
                    .on('click', function (e) {
                        map.removeLayer(this);
                    })

                @if ($entry->image)
                entry_{{ $entry->id }}.imageid = {{ $entry->image->id }};
                entry_{{ $entry->id }}.alt = '{{ $entry->image->title }}';

                entry_{{ $entry->id }}.bindPopup(
                    `<img src="https://sipi.participatory-archives.chh/{{$entry->image->base_path}}/{{$entry->image->signature}}.jp2/full/320,/0/default.jpg"/>`,
                        {
                            minWidth: 320,
                            closeButton: false
                        }
                    )
                    .on('mouseover', function (e) {
                        this.openPopup();
                    });
                @endif

                entry_{{ $entry->id }}.addTo(map);
            @endif
        @endforeach
    })

    document.addEventListener('alpine:init', () => {

        Alpine.data('app', () => ({

            minimize_collection: false,

            // methods
            init() {
                
            },

            dragstart(evt) {
                let img = evt.target,
                    data = {
                        'id': img.id,
                        'src': img.src,
                        'alt': img.alt
                    };

                evt.dataTransfer.setData('text', JSON.stringify(data));
            },

            dragover(evt) {
                evt.preventDefault();
                evt.dataTransfer.dropEffect = 'move';
            },

            drop(evt) {
                evt.preventDefault();

                let img = JSON.parse(evt.dataTransfer.getData("text/plain"));

                var rect = evt.target.getBoundingClientRect();
                var x = evt.clientX - rect.left;
                var y = evt.clientY - rect.top;


                let coordinates = map.containerPointToLatLng(L.point([x, y]));

                let entry = new custom_entry(coordinates).addTo(map);

                entry.imageid = img.id;
                entry.alt = img.alt;

                entry.bindPopup(
                    `<img src="${img.src}" alt="${img.alt}"/>`,
                        {
                            minWidth: 320,
                            closeButton: false
                        }
                    )
                    .on('mouseover', function (e) {
                        this.openPopup();
                    });
            },

            update_and_submit() {
                let data = [];

                map.eachLayer(function (layer) { 
                    if(layer instanceof L.Marker) {
                        data.push({
                            id: layer.imageid,
                            alt: layer.alt,
                            coordinates: {
                                latitude: layer.getLatLng().lat,
                                longitude: layer.getLatLng().lng
                            }
                        })
                    }
                });

                this.$refs.markerdata.value = JSON.stringify(data);
                this.$refs.markerform.submit();
            }
        }));
    });

</script>
@endsection