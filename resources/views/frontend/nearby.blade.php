@extends('base')

@section('content')

<div class="p-4">

    <div class="mb-4 text-right">
        <label for="accuracy">Distance in meter: <span id="distance_value"></span></label> 
        <input type="range" min="100" max="10000" value="100" step="100" id="distance" name="distance">
        <button type="button" class="p-2 border border-black" onclick="get_location()">Reload with current position</button>
    </div>

    @if(isset($images))
    <div class="mb-4" style="height: 50vh;">
        @include('collections.partials.display-map', ['images' => $images, 'height' => 'h-full'])
    </div>
    <div>
        @include('collections.partials.display-grid', ['images' => $images])
    </div>
    @endif
</div>

@endsection

@section('scripts')

<script>

const options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
};

function success(pos) {
    const crd = pos.coords;

    window.location = '?latitude='+crd.latitude+'&longitude='+crd.longitude+'&distance='+document.querySelector('#distance').value;
}

function error(err) {
    console.warn(`ERROR(${err.code}): ${err.message}`);
}

function get_location() {
    navigator.geolocation.getCurrentPosition(success, error, options);
    if ( navigator.permissions && navigator.permissions.query) {
      //try permissions APIs first
      navigator.permissions.query({ name: 'geolocation' }).then(function(result) {
          // Will return ['granted', 'prompt', 'denied']
          const permission = result.state;
          if ( permission === 'granted' || permission === 'prompt' ) {
              _onGetCurrentLocation();
          }
      });
    } else if (navigator.geolocation) {
      //then Navigation APIs
      _onGetCurrentLocation();
    }
}

function _onGetCurrentLocation () {
    const options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
    };

    navigator.geolocation.getCurrentPosition(success, error, options);

}

document.addEventListener('DOMContentLoaded', () => {
    if (window.location.href.indexOf('latitude') == -1) {
        get_location();
    }

    var slider = document.getElementById("distance");
    var output = document.getElementById("distance_value");
    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
    }

    const params = new URLSearchParams(window.location.search);

    if(params.get('distance')) {
        slider.value = parseInt(params.get('distance'));
        output.innerHTML = slider.value;
    }
});

</script>

@endsection
