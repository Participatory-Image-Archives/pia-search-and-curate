@extends('base')

@section('content')
<div class="p-4">
    <div class="w-full mb-10">
        <p>Your selection of the view becomes the bounding box from which the coordinates are generated.</p>
    </div>
    <div class="flex">
        <div id="map" style="height: 600px; width: 600px;"></div>
        <div class="ml-4">
            <table class="mb-4">
                <tr>
                    <td>Top left corner</td>
                    <td>
                        <span class="top_left_latitude"></span>
                        <span class="top_left_longitude"></span>
                    </td>
                </tr>
                <tr>
                    <td>Bottom right corner</td>
                    <td>
                        <span class="bottom_right_latitude"></span>
                        <span class="bottom_right_longitude"></span>
                    </td>
                </tr>
            </table>
            <x-links.cta id="search" href="javascript:;" label="Search with these coordinates"/>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        
        let center = [46.818188, 8.227512],
            map = L.map('map', {
                center: center,
                zoom: 8,
                maxZoom: 18,
                doubleClickZoom: false
            });

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/light-v10',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoidGhnaWV4IiwiYSI6ImNrcjF5Z2ZxZDI2bWYydnFhMWw0eDV2YjIifQ.CZJtOXLy-IZeI6a5ia8Lzw'
        }).addTo(map);

        map.on('moveend', function(e) {
            var bounds = map.getBounds();
            
            document.querySelector('.top_left_latitude').innerHTML = bounds.getNorthWest().lat;
            document.querySelector('.top_left_longitude').innerHTML = bounds.getNorthWest().lng;
            document.querySelector('.bottom_right_latitude').innerHTML = bounds.getSouthEast().lat;
            document.querySelector('.bottom_right_longitude').innerHTML = bounds.getSouthEast().lng;

            document.querySelector('#search').href = '/?coordinates='+bounds.getNorthWest().lat+','+bounds.getNorthWest().lng+','+bounds.getSouthEast().lat+','+bounds.getSouthEast().lng;
        });


    });

</script>
@endsection