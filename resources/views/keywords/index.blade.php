@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-4">
        <h2 class="text-2xl mb-2 md:w-1/2">
            Keywords
        </h2>
        <div class="md:w-1/2 md:text-right">
            <a href="{{ route('collections.index') }}"
                class="ml-2 inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white">Collections</a>
            <a class="ml-2 inline-block py-1 text-xs mb-2 underline" href="/">
                ///
            </a>
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
            <a href="/?keyword={{ $keyword->id }}"
            class="inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white ml-2 mb-2">{{ $keyword->label }}</a>
        @endif
    @endforeach
</div>
@endsection