@extends('base')

@section('content')
<div class="p-4">
    @php
        $current = '';
    @endphp
    @foreach ($docs as $doc)
        @if ($doc->label)
            @if ($current != $doc->label[0])
                @php
                    $current = $doc->label[0];
                @endphp
                <h2 class="text-2xl mt-2 mb-2">{{ $current }}</h2>
            @endif
            <x-links.default :href="route('docs.edit', [$doc])" :label="$doc->label"/>
        @endif
    @endforeach
</div>
@endsection