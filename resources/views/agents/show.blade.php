@extends('base')

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex" id="searchable-list" >
        <div class="fixed h-screen w-1/2 overflow-hidden">
            
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            <div class="pt-14 pb-20 pl-14 pr-4">
                <div class="relative flex items-center justify-between mb-12 ">
                    <h2 class="text-4xl text-center">
                        {{ $agent->name }}
                    </h2>
                </div>

                <div class="mb-10">
                    <span class="text-xs">View </span>
                    <x-links.bare label="API JSON" href="{{ env('API_URL') }}agents/{{ $agent->id }}" target="_blank"/>, 
                    <x-links.bare label="All related images ({{ $image_count }})" href="/?agent={{ $agent->id }}"/>
                </div>

                <table class="w-full">
                    <thead class="text-xs">
                        <tr>
                            <td class="pb-2 w-1/3">Field</td>
                            <td class="pb-2">Value</td>
                        </tr>
                    </thead>
                    <tr>
                        <td class="pt-2">Title</td>
                        <td>{!! $agent->title ?? '&mdash;' !!}</td>
                    </tr>
                    <tr>
                        <td class="pt-2">Family</td>
                        <td>{!! $agent->family ?? '&mdash;' !!}</td>
                    </tr>
                    <tr>
                        <td class="pt-2">Birthhdate</td>
                        <td>
                            @if ($agent->birthdate)
                                <span class="inline-block py-1 px-3 text-xs rounded-full bg-black text-white mr-2 mb-2">
                                @if ($agent->birthdate->date)
                                    {{ date('d. M Y', strtotime($agent->birthdate->date)); }}
                                @endif
                                @if ($agent->birthdate->end_date)
                                    {{ date('d. M Y', strtotime($agent->birthdate->end_date)); }}
                                @endif
                                </span>
                                @if ($agent->birthdate->date_string)
                                    <span class="inline-block py-1 px-3 text-xs underline mr-2 mb-2">{{ $agent->birthdate->date_string }}</span>
                                @endif
                            @else
                                &mdash;
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="pt-2">Birthplace</td>
                        <td>
                            @if ($agent->birthplace)
                                <x-links.default :label="$agent->birthplace->label" href="{{ route('places.show', [$agent->birthplace]) }}" class="mb-2 name"/>
                            @else
                                &mdash;
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="pt-2">Deathhdate</td>
                        <td>
                            @if ($agent->deathdate)
                                <span class="inline-block py-1 px-3 text-xs rounded-full bg-black text-white mr-2 mb-2">
                                @if ($agent->deathdate->date)
                                    {{ date('d. M Y', strtotime($agent->deathdate->date)); }}
                                @endif
                                @if ($agent->deathdate->end_date)
                                    {{ date('d. M Y', strtotime($agent->deathdate->end_date)); }}
                                @endif
                                </span>
                                @if ($agent->deathdate->date_string)
                                    <span class="inline-block py-1 px-3 text-xs underline mr-2 mb-2">{{ $agent->deathdate->date_string }}</span>
                                @endif
                            @else
                                &mdash;
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="pt-2">Deathplace</td>
                        <td>
                            @if ($agent->deathplace)
                                <x-links.default :label="$agent->deathplace->label" href="{{ route('places.show', [$agent->deathplace]) }}" class="mb-2 name"/>
                            @else
                                &mdash;
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="pt-2">GND URI</td>
                        <td>
                            @if($agent->gnd_uri)
                                <a href="{{ $agent->gnd_uri }}" target="_blank" class="underline">{{ $agent->gnd_uri }}</a>
                            @else
                            &mdash;
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="pt-2 align-top">Description</td>
                        <td class="pt-2">{!! $agent->description ?? '&mdash;' !!}</td>
                    </tr>
                </table>

                <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-700 bg-white">
                    <a class="hover:underline" href="{{ route('agents.edit', [$agent]) }}">Edit info</a>
                    <form action="{{ route('agents.destroy', [$agent]) }}" method="post" class="inline-block">
                        @csrf
                        @method('delete')
    
                        <x-buttons.delete/>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <aside id="sidebar"
        x-data="{expand_collections: false}"
        @mouseleave="expand_collections = false;"
        class="flex fixed top-0 right-0 transform transition min-h-screen shadow-2xl z-50 print-hidden">
        
        <livewire:collections-aside />
    </aside>
</div>
@endsection