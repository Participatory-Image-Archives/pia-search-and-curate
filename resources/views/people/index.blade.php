@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-4">
        <h2 class="text-2xl mb-2 md:w-1/2">
            People
        </h2>
        <div class="md:w-1/2 md:text-right">
            @include('partials.lists-dropdown')
            <x-links.default label="Home" href="/"/>
        </div>
    </div>

    @php
        $current = '';
    @endphp
    <div id="searchable-list">
        <input class="search border-b border-black mb-8 focus:outline-none" placeholder="Search…"/>
        <ul class="list">
        @foreach ($people as $person)
            @if ($person->name)
                @if ($current != $person->name[0])
                    @php
                        $current = $person->name[0];
                    @endphp
                    <h2 class="text-2xl mt-2 mb-2">{{ $current }}</h2>
                @endif
                <li class="inline">
                    <x-links.default :label="$person->name" href="/?person={{ $person->id }}" class="mb-2 name"/>
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
                valueNames: ['name']
            });
        });

    </script>
@endsection
