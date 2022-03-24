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
    <div class="flex" id="searchable-list" >
        <div class="fixed h-screen w-1/2 overflow-hidden">
            <div id="iiif-image" class="w-full min-h-full"></div>
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            @include('images.partials.details')
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
