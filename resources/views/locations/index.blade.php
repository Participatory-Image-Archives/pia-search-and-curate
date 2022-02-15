@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-4">
        <h2 class="text-2xl mb-2 md:w-1/2">
            Locations
        </h2>
        
    </div>

    @php
        $current = '';
    @endphp
    <div id="searchable-list" class="pb-20">
        <input class="search border-b border-black mb-8 focus:outline-none" placeholder="Search…"/>

        <div id="filter" class="mt-2 mb-6">
            <span class="inline-block mb-2 text-xs"><a href="https://www.geonames.org/export/codes.html" target="_blank" class="underline">Geoname Codes</a></span>
            <ul>
                @foreach ($codes as $key => $code)
                    <a onclick="filter(event)" href="javascript:;" class="rounded-full border border-gray-900 text-xs px-2 py-1" data-term="{{ $key }}" data-field="code">{{ $key }}: {{ $code }}</a>
                @endforeach
            </ul>
        </div>

        <div id="filter" class="mt-2 mb-6">
            <span class="inline-block mb-2 text-xs">Geoname Division Levels</span>
            <ul>
                @foreach ($levels as $key => $level)
                <a onclick="filter(event)" href="javascript:;" class="rounded-full border border-gray-900 text-xs px-2 py-1" data-term="level-{{ $key }}" data-field="level">{{ $key }}: {{ $level }}</a>
                @endforeach
            </ul>
        </div>

        <ul class="list">
        @foreach ($locations as $location)
            @if ($location->label)
                @if ($current != $location->label[0])
                    @php
                        $current = $location->label[0];
                    @endphp
                    <h2 class="text-2xl mt-2 mb-2">{{ $current }}</h2>
                @endif
                <li class="inline">
                    <x-links.default :label="$location->label" :href="route('locations.show', [$location])" class="mb-2 name"/>
                    <span class="code hidden">{{ $location->geonames_code }}</span>
                    <span class="level hidden">level-{{ $location->geonames_division_level }}</span>
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

        let searchable_list, filter_target;

        document.addEventListener('DOMContentLoaded', () => {
            searchable_list = new List('searchable-list', {
                valueNames: ['name', 'code', 'level']
            });
        });

        function filter(evt) {

            if(filter_target) {
                filter_target.classList.remove('bg-gray-900', 'text-white')
            }

            if(searchable_list.filtered) {
                searchable_list.filter()
            }

            if(filter_target && filter_target == evt.currentTarget) {
                filter_target = undefined
                return
            }

            filter_target = evt.currentTarget;
            
            let field = filter_target.dataset.field,
                term = filter_target.dataset.term;

            filter_target.classList.add('bg-gray-900', 'text-white')

            searchable_list.filter(function(item) {
                if (item.values()[field] == term) {
                    return true;
                } else {
                    return false;
                }
            });
        }

    </script>
@endsection