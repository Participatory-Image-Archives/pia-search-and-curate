<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIA Search and Curate</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    
    @yield('styles')

</head>

<body>
    
<div id="map" class="mb-4" style="height: 400px; width: 400px;"></div>
<x-links.cta id="search" href="javascript:;" label="Search on this map section" target="_top"/>

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
            
            document.querySelector('#search').href = '/?coordinates='+bounds.getNorthWest().lat+','+bounds.getNorthWest().lng+','+bounds.getSouthEast().lat+','+bounds.getSouthEast().lng;
        });


    });

</script>

</body>
</html>