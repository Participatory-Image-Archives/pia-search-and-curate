@extends('base')

@section('content')

<div class="p-4">

    <div class="mb-4 text-right">
        <label for="accuracy">Accuracy</label> 
        <select id="accuracy" name="accuracy" class="p-2 mb-2 border border-black">
            <option value="1">~ 10m</option>
            <option value="2" selected>~ 100m</option>
            <option value="3">~ 1km</option>
        </select> 
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

    window.location = '?latitude='+crd.latitude+'&longitude='+crd.longitude+'&accuracy='+document.querySelector('#accuracy').value;
}

function error(err) {
    console.warn(`ERROR(${err.code}): ${err.message}`);
}

function get_location() {
    navigator.geolocation.getCurrentPosition(success, error, options);

}

document.addEventListener('DOMContentLoaded', () => {
    if (window.location.href.indexOf('latitude') == -1) {
        get_location();
    }
});

</script>

@endsection
