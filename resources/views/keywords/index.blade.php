@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-4">
        <h2 class="text-2xl mb-2 md:w-1/2">
            Keywords
        </h2>
        <div class="md:w-1/2 md:text-right">
            <x-links.default label="Collections" :href="route('collections.index')"/>
            <x-links.default label="Home" href="/"/>
        </div>
    </div>

    @php
        $current = '';
    @endphp
    @foreach ($keywords as $keyword)
        @if ($keyword->label)
            @if ($current != $keyword->label[0])
                @php
                    $current = $keyword->label[0];
                @endphp
                <h2 class="text-2xl mt-2 mb-2">{{ $current }}</h2>
            @endif
            <x-links.default :label="$keyword->label" href="/?keyword={{ $keyword->id }}" class="mb-2"/>
        @endif
    @endforeach
</div>
@endsection