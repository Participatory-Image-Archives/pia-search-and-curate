@extends('base')

@section('content')
<div class="p-4 pb-20">
    <div class="md:flex mb-4">
        <h2 class="text-2xl mb-2 md:w-1/2">
            Notes
        </h2>
        
    </div>

    @php
        $current = '';
    @endphp
    <div id="searchable-list">
        <input class="search border-b border-black mb-8 focus:outline-none" placeholder="Search…"/>
        <ul class="list">
        @foreach ($notes as $note)
            @if ($note->label)
                @if ($current != $note->label[0])
                    @php
                        $current = $note->label[0];
                    @endphp
                    <h2 class="text-2xl mt-2 mb-2">{{ $current }}</h2>
                @endif
                <li class="inline">
                    <x-links.default :label="$note->label" href="{{ route('notes.edit', [$note]) }}" class="mb-2 label"/>
                </li>
            @endif
        @endforeach
        </ul>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('node_modules/list.js/dist/list.min.js') }}"></script>
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            var searchable_list = new List('searchable-list', {
                valueNames: ['label']
            });
        });

    </script>
@endsection