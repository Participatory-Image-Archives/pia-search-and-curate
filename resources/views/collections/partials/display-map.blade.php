<div class="map h-full">
    <div id="map" class="w-full {{ $height ?? 'min-h-screen' }}"></div>
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

        L.control.scale().addTo(map);

        @foreach ($images as $image)
            @if ($image->place)
                let image_{{ $image->id }}_{{ $loop->index }} = new L.Marker([{{$image->place->latitude}}, {{$image->place->longitude}}]);

                image_{{ $image->id }}_{{ $loop->index }}
                    .bindTooltip('{{ $image->title }}')
                    .bindPopup(
                        `<a href="{{ route('images.show', [$image]) }}"><img src="https://sipi.participatory-archives.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/320,/0/default.jpg"/></a>`,
                        {
                            minWidth: 320,
                            closeButton: false
                        }
                    )
                    .on('mouseover', function (e) {
                        this.openPopup();
                    });

                markers.addLayer(image_{{ $image->id }}_{{ $loop->index }});
            @endif
        @endforeach

        if (window.location.href.indexOf('latitude') > -1) {
            const params = new URLSearchParams(window.location.search);

            let position = L.circleMarker([
                params.get('latitude'),
                params.get('longitude')
            ]);

            markers.addLayer(position);
        }

        map.fitBounds(markers.getBounds());

    });

</script>
