@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-4">
        <div class="md:w-1/2">
            <h2 class="text-2xl mb-2">
                {{ $collection->label }}
            </h2>
            <div>
                <x-links.cta label="Back" :href="route('collections.show', [$collection])"/>
            </div>
        </div>
        <div class="md:w-1/2 md:text-right">
            @include('partials.lists-dropdown')
            <x-links.default label="Home" href="/"/>
        </div>
    </div>
    <div class="w-full mb-10">
        {!! $collection->description !!}
    </div>
    <div class="flex">
        <div class="w-1/6 mr-8">
            <p class="text-xs mb-2">These images do not have a location attached.</p>
            @foreach ($collection->images as $image)
                @if (!$image->location)
                <a href="{{ route('images.show', [$image]) }}">
                    <img class="inline-block mb-2 w-full" src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/320,/0/default.jpg" alt="{{$image->title}}" title="{{$image->title}}">
                </a>
                @endif
            @endforeach
        </div>
        <div class="w-full">
            <div id="map" class="w-full" style="height: 800px"></div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
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
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        
        let center = [46.818188, 8.227512],
            map = L.map('map', {
                center: center,
                zoom: 8,
                maxZoom: 18,
                doubleClickZoom: false
            }),
            markers = L.markerClusterGroup();

        map.addLayer(markers);
        map.doubleClickZoom.disable(); 

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/light-v10',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoidGhnaWV4IiwiYSI6ImNrcjF5Z2ZxZDI2bWYydnFhMWw0eDV2YjIifQ.CZJtOXLy-IZeI6a5ia8Lzw'
        }).addTo(map);

        @foreach ($collection->images as $image)
            @if ($image->location)
                let image_{{ $image->id }} = new L.Marker([{{$image->location->latitude}}, {{$image->location->longitude}}]);

                image_{{ $image->id }}
                    .bindTooltip('{{ $image->title }}')
                    .addEventListener('click', function(e) {
                        window.location = '{{ route('images.show', [$image]) }}';
                    })
                    .bindPopup(
                        `<img src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/320,/0/default.jpg"/>`,
                        {
                            minWidth: 320,
                            closeButton: false
                        }
                    )
                    .on('mouseover', function (e) {
                        this.openPopup();
                    });

                markers.addLayer(image_{{ $image->id }});
            @endif
        @endforeach

    });

</script>
@endsection