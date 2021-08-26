@extends('frontend/base')

@section('styles')
    <link rel="stylesheet" href="node_modules/leaflet/dist/leaflet.css">
    <style>

        html, body {
            width: 100%;
            height: 100%;
        }
        #map {
            width: 100%;
            height: 100%;
        }
        .leaflet-popup-content-wrapper {
            padding: 0;
        }
        .leaflet-popup-content {
            margin: 0;
        }
        .leaflet-popup-content > img {
            min-width: 200px;
            height: auto;
        }

    </style>
@endsection

@section('content')
    <div id="map"></div>
@endsection

@section('scripts')
    <script src="node_modules/leaflet/dist/leaflet.js"></script>
    <script src="node_modules/papaparse/papaparse.js"></script>
    <script>

        const api_url = 'api/ids',
              params = new URLSearchParams(window.location.search);
        
        let center = [46.818188, 8.227512],
            map = L.map('map').setView(center, 9);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/light-v10',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoidGhnaWV4IiwiYSI6ImNrcjF5Z2ZxZDI2bWYydnFhMWw0eDV2YjIifQ.CZJtOXLy-IZeI6a5ia8Lzw'
        }).addTo(map);

        function render_map(selection){
            if(!selection.length) {
                alert('Keine Auswahl getroffen.');
                return;
            }

            let xmlhttp = new XMLHttpRequest(),
                url = api_url+'/?ids='+selection.join(',')+'&geo';

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    selected_data = JSON.parse(this.responseText);

                    console.log(selected_data);

                    selected_data.forEach((el, i) => {
                        let circle = L.circle([el.latitude, el.longitude], {
                            color: 'blue',
                            radius: 750
                        }).addTo(map);

                        var popup = L.popup()
                            .setLatLng([el.latitude, el.longitude])
                            .setContent('<img src="https://data.dasch.swiss/core/sendlocdata.php?qtype=full&reduce=3&res='+el.salsah_id+'"/>');

                        circle.bindPopup(popup);
                    });


                    let asv_locations = window.location.protocol+'//'+window.location.host
                        +'/OrtsverzeichnisASV_Stand210701_resolved.csv';
                    Papa.parse(asv_locations, {
                        download: true,
                        header: true,
                        complete: function(results) {
                            results.data.forEach((el, i) => {
                                //let marker = L.marker([parseFloat(el['latitude']), parseFloat(el['longitude'])]).addTo(map);
                                let circle = L.circle([el.latitude, el.longitude], {
                                    color: 'red',
                                    radius: 250
                                }).addTo(map);
                                circle.bindPopup(el.name);
                            })
                        }
                    });
                }
            };
            xmlhttp.open('GET', url, true);
            xmlhttp.send();
        }

        render_map(params.get('ids').split(','));

    </script>
@endsection
