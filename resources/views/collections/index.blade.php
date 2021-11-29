@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-4">
        <h2 class="text-2xl mb-2 md:w-1/2">
            Collections
        </h2>
        <div class="md:w-1/2 md:text-right">
            @include('partials.lists-dropdown')
            <x-links.default label="Home" href="/"/>
        </div>
    </div>
    @php
        $current = '';
    @endphp
    @foreach ($collections as $collection)
        @if ($collection->label)
            @if ($current != $collection->label[0])
                @php
                    $current = $collection->label[0];
                @endphp
                <h2 class="text-2xl mt-2 mb-2">{{ $current }}</h2>
            @endif
            <a href="{{ route('collections.show', [$collection]) }}"
                class="inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white ml-2 mb-2">{{ $collection->label }}</a>
        @endif
    @endforeach
</div>
@endsection