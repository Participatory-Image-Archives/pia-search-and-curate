@extends('base')

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex" id="searchable-list" >
        <div class="fixed h-screen w-1/2 overflow-hidden">
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            <div class="pt-14 pb-20 pl-14 pr-4">

                <form action="{{ route('agents.update', [$agent]) }}" method="post">
                    @csrf
                    @method('patch')

                <div class="relative flex items-center justify-between mb-12 ">
                    <h2 class="text-4xl text-center">
                        <input type="text" name="name" value="{{ $agent->name ?? '' }}" class="w-full border border-gray-300 p-1 px-2">
                    </h2>
                </div>

                <table class="w-full">
                    <thead class="text-xs">
                        <tr>
                            <td class="pb-2 w-1/3">Field</td>
                            <td class="pb-2">Value</td>
                        </tr>
                    </thead>
                    <tr>
                        <td>Title</td>
                        <td>
                            <input type="text" name="title" value="{{ $agent->title ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td>Family</td>
                        <td>
                            <input type="text" name="family" value="{{ $agent->family ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2 mb-4">
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top">Birthplace</td>
                        <td>
                            <select name="birthplace_id" class="w-full slim">
                                <option value="">-</option>
                                @foreach ($places as $place)
                                    <option value="{{ $place->id }}" {{ ($agent->birthplace && $agent->birthplace->id == $place->id) ? 'selected' : '' }}>{{ $place->label }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="append_birthplace" placeholder="Name of new place" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top">Birthdate</td>
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
                            <div class="flex items-center pb-4">
                                <input type="date" name="append_birthdate" placeholder="Add new date" class="w-full border border-gray-300 p-1 px-2">
                                <label for="remove_birthdate" class="w-72 pl-2">
                                    <input type="checkbox" name="remove_birthdate">
                                    Remove Birthdate
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top">Deathplace</td>
                        <td>
                            <select name="deathplace_id" class="w-full slim">
                                <option value="">-</option>
                                @foreach ($places as $place)
                                    <option value="{{ $place->id }}" {{ ($agent->deathplace && $agent->deathplace->id == $place->id) ? 'selected' : '' }}>{{ $place->label }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="append_deathplace" placeholder="Name of new place" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top">Deathdate</td>
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
                            <div class="flex items-center mb-4">
                                <input type="date" name="append_deathdate" placeholder="Add new date" class="w-full border border-gray-300 p-1 px-2">
                                <label for="remove_deathdate" class="w-72 pl-2">
                                    <input type="checkbox" name="remove_deathdate">
                                    Remove Deathdate
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="https://lobid.org/gnd/search?q={{ $agent->name }}" target="_blank" class="underline">GND URI</a></td>
                        <td>
                            <input type="url" name="gnd_uri" value="{{ $agent->gnd_uri ?? '' }}" class="w-full mt-1 border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td class="pt-2 align-top">Description</td>
                        <td class="pt-2">
                            <textarea name="description" id="description" class="border border-gray-500 p-2 w-full h-60" placeholder="What can you say about this collection?">{{ $agent->description ?? '' }}</textarea>
                        </td>
                    </tr>
                    
                    <!-- TODO
                    birthdate_id
                    deathdate_id
                    -->
                </table>

                <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-300 bg-white">
                    <button type="submit" class="hover:underline">Save info</button>
                    <a href="javascript:history.back()" class="underline">Cancel</a>
                </div>

                </form>
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

@section('scripts')
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('select.slim').forEach(el => {
                console.log(el)
                new SlimSelect({
                    select: el,
                });
            });
        });

    </script>
@endsection