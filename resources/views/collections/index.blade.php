@extends('base')

@section('content')
<div class="p-4">
    <ul>
        @foreach ($collections as $collection)
            <li><a href="/?c={{ $collection->id }}">{{ $collection->label }}</a></li>
        @endforeach
    </ul>
</div>
@endsection