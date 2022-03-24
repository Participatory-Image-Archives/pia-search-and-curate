@extends('base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('node_modules/leaflet/dist/leaflet.css') }}">
    <style>

        .leaflet-container {
            background: #181818;
        }

    </style>
@endsection

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex" id="searchable-list" >
        <div class="fixed h-screen w-1/2 overflow-hidden">
            <div id="iiif-image" class="w-full min-h-full"></div>
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            <div class="pt-14 pb-20 pl-14 pr-4">

                <form action="{{ route('images.update', [$image]) }}" method="post">
                    @csrf
                    @method('patch')

                <div class="mb-12 ">
                    <h2 class="text-4xl text-center w-full">
                        <input type="text" name="title" value="{{ $image->title ?? '' }}" class="w-full border border-gray-300 p-1 px-2">
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
                        <td>SALSAH ID</td>
                        <td>
                            <input type="number" name="salsah_id" value="{{ $image->salsah_id ?? '' }}" class="w-full border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td>Old Nr</td>
                        <td>
                            <input type="text" name="oldnr" value="{{ $image->oldnr ?? '' }}" class="w-full border border-gray-300 p-1 px-2">
                        </td>
                    </tr>
                    <tr>
                        <td>Sequence Number</td>
                        <td>
                            <input type="text" name="sequence_number" value="{{ $image->sequence_number ?? '' }}" class="w-full border border-gray-300 p-1 px-2">
                        </td>
                    </tr>

                    {{--<tr>
                        <td class="w-1/3">Verso</td>
                        <td>
                            @if ($image->verso)
                                <a href="{{ route('images.show', [$id => $image->verso]) }}">Verso</a>
                            @else
                            –
                            @endif
                        </td>
                    </tr>--}}
                    <tr>
                        <td class="w-1/3">Object Type</td>
                        <td>
                            <select name="object_type_id" class="w-full slim">
                                <option value="">-</option>
                                @foreach ($object_types as $object_type)
                                    <option value="{{ $object_type->id }}" {{ ($image->objectType && $image->objectType->id == $object_type->id) ? 'selected' : '' }}>{{ $object_type->label }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Model Type</td>
                        <td>
                            <select name="model_type_id" class="w-full slim">
                                <option value="">-</option>
                                @foreach ($model_types as $model_type)
                                    <option value="{{ $model_type->id }}" {{ ($image->modelType && $image->modelType->id == $model_type->id) ? 'selected' : '' }}>{{ $model_type->label }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Format</td>
                        <td>
                            <select name="format_id" class="w-full slim">
                                <option value="">-</option>
                                @foreach ($formats as $format)
                                    <option value="{{ $format->id }}" {{ ($image->format && $image->format->id == $format->id) ? 'selected' : '' }}>{{ $format->label }} / {{ $format->comment }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </table>
                
                <div class="my-6">
                    <h3 class="mb-1 text-xs">Keywords</h3>
                    <div class="mb-2">
                        <select name="keywords[]" class="w-full slim" multiple>
                            <option value="">-</option>
                            @foreach ($keywords as $keyword)
                                <option value="{{ $keyword->id }}" {{ $image->keywords->contains($keyword->id) ? 'selected' : '' }}>{{ $keyword->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-2 text-xs">Collections</h3>
                    <div>
                        <select name="collections[]" class="w-full slim" multiple>
                            <option value="">-</option>
                            @foreach ($collections as $collection)
                                <option value="{{ $collection->id }}" {{ $image->collections->contains($collection->id) ? 'selected' : '' }}>{{ $collection->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                

                <div class="mb-6">
                    <h3 class="mb-2 text-xs">Comments</h3>
                    @foreach ($image->comments as $comment)
                        @if ($comment->comment)
                            <p class="mb-2 text-sm">– {{ $comment->comment }}</p>
                        @endif
                    @endforeach
                    <textarea name="append_comment" placeholder="Add new comment" class="w-full mt-1 border border-gray-300 p-1 px-2"></textarea>
                </div>
                
                <div class="mb-6">
                    <h3 class="mb-2 text-xs">People</h3>
                    <div>
                        <select name="people[]" class="w-full slim" multiple>
                            <option value="">-</option>
                            @foreach ($people as $person)
                                <option value="{{ $person->id }}" {{ $image->people->contains($person->id) ? 'selected' : '' }}>{{ $person->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="append_person" placeholder="Name of new person" class="w-full mt-1 border border-gray-300 p-1 px-2">
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-2 text-xs">Date</h3>
                    <div>
                        <div class="flex">
                            @forelse ($image->dates as $date)
                                @if ($date->date)
                                    <span class="inline-block py-1 px-3 text-xs rounded-full bg-black text-white mr-2 mb-2">{{ date('d. M Y', strtotime($date->date)); }}</span>
                                @endif
                                @if ($date->date_string)
                                    <span class="inline-block py-1 px-3 text-xs underline mr-2 mb-2">{{ $date->date_string }}</span>
                                @endif
                            @empty
                            –
                            @endforelse
                        </div>
                        <input type="date" name="append_date" placeholder="Add new date" class="w-full border border-gray-300 p-1 px-2">
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="mb-2 text-xs">Location</h3>
                    <div>
                        <select name="location_id" class="w-full slim">
                            <option value="">-</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ ($image->location && $image->location->id == $location->id) ? 'selected' : '' }}>{{ $location->label }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="append_location" placeholder="Name of new location" class="w-full mt-1 border border-gray-300 p-1 px-2">
                    </div>
                </div>

                <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-300 bg-white">
                    <button type="submit" class="hover:underline">Save info</button>
                    <a class="hover:underline" href="{{ route('images.show', [$image]) }}">View image</a>
                </div>

                </form>
                
            </div>
        </div>
    </div>

    <aside id="sidebar"
        x-data="{expand_collections: false}"
        @mouseleave="expand_collections = false;"
        class="flex fixed top-0 right-0 transform transition min-h-screen shadow-2xl z-50 print-hidden">
        
        @include('frontend.partials.aside-collections')
    </aside>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('node_modules/leaflet/dist/leaflet.js') }}"></script>
    <script src="{{ asset('node_modules/leaflet-iiif/leaflet-iiif.js') }}"></script>

    <script>

        document.addEventListener('DOMContentLoaded', () => {
            var image = L.map('iiif-image', {
                center: [0, 0],
                crs: L.CRS.Simple,
                zoom: 0,
            });

            L.tileLayer.iiif('https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/info.json').addTo(image);

            document.querySelectorAll('select.slim').forEach(el => {
                console.log(el)
                new SlimSelect({
                    select: el,
                });
            });
        })

    </script>
@endsection