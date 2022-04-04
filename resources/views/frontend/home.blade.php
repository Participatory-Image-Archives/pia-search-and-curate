@extends('base')

@section('content')

<div id="app" class="p-4 bg-gray-100 min-h-screen" x-data="app">

    <livewire:search />

</div>

{{--

@if($tagcloud)
<script src="{{ asset('js/wordcloud2.js') }}"></script>
@endif

--}}

@endsection
