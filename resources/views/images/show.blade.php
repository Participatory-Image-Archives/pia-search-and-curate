@extends('base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('node_modules/leaflet/dist/leaflet.css') }}">
    <style>

        .leaflet-container {
            background: #181818;
        }

    </style>
@endsection

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex flex-col md:flex-row" id="searchable-list" >
        <div class="md:fixed h-80 md:h-screen md:w-1/2 overflow-hidden">
            <div id="iiif-image" class="w-full min-h-full"></div>
        </div>

        <div class="md:fixed md:left-1/2 md:h-screen md:w-1/2 md:pr-14 bg-white overflow-y-auto">
            @include('images.partials.details')
        </div>
    </div>

    <aside id="sidebar">
        <livewire:collections-aside />
    </aside>
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
