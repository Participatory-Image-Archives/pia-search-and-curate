@extends('base')

@section('content')
<div class="p-4">
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
            <a href="/?keyword={{ $keyword->id }}"
            class="inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white ml-2 mb-2">{{ $keyword->label }}</a>
        @endif
    @endforeach
</div>
@endsection