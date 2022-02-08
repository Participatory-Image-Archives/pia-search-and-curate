@extends('base')

@section('content')
<div class="p-4">

    <form action="{{ route('images.update', [$image]) }}" method="post">
        @csrf
        @method('patch')

        <div class="md:flex mb-10">
            <div class="md:w-1/2">
                <h2 class="text-2xl mb-2">
                    {{ $image->title }}
                </h2>
                <div>
                    <x-links.cta label="View" :href="route('images.show', [$image])"/>
                    <x-buttons.default type="submit" label="Save"/>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="w-full md:w-1/2">
                    <img class="inline-block mr-2 w-full shadow-2xl" src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/640,/0/default.jpg" alt="{{ $image->title }}" title="{{ $image->title }}">
            </div>
            <div class="w-full md:w-1/2">

                <div class="py-4 px-6">

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
                                    <input type="number" name="salsah_id" value="{{ $image->salsah_id ?? '' }}" class="w-full">
                                </td>
                            </tr>
                            <tr>
                                <td>Old Nr</td>
                                <td>
                                    <input type="text" name="oldnr" value="{{ $image->oldnr ?? '' }}" class="w-full">
                                </td>
                            </tr>
                            <tr>
                                <td>Signature</td>
                                <td>
                                    <input type="text" name="signature" value="{{ $image->signature ?? '' }}" class="w-full">
                                </td>
                            </tr>
                            <tr>
                                <td>Title</td>
                                <td>
                                    <input type="text" name="title" value="{{ $image->title ?? '' }}" class="w-full">
                                </td>
                            </tr>
                            <tr>
                                <td>Sequence Number</td>
                                <td>
                                    <input type="text" name="sequence_number" value="{{ $image->sequence_number ?? '' }}" class="w-full">
                                </td>
                            </tr>
                        </table>

                        <hr class="my-4">

                        <table class="w-full">
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
                        
                        <hr class="my-4">

                        <div>
                            <h3 class="mb-1 text-xs">Keywords</h3>
                            <div class="mb-2">
                                <select name="keywords[]" class="w-full slim" multiple>
                                    <option value="">-</option>
                                    @foreach ($keywords as $keyword)
                                        <option value="{{ $keyword->id }}" {{ $image->keywords->contains($keyword->id) ? 'selected' : '' }}>{{ $keyword->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <h3 class="mb-1 text-xs">Collections</h3>
                            <div class="mb-2">
                                <select name="collections[]" class="w-full slim" multiple>
                                    <option value="">-</option>
                                    @foreach ($collections as $collection)
                                        <option value="{{ $collection->id }}" {{ $image->collections->contains($collection->id) ? 'selected' : '' }}>{{ $collection->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        {{--<hr class="my-4">

                        <div>
                            <h3 class="mb-1 text-xs">Comments</h3>
                            @forelse ($image->comments as $comment)
                                @if ($comment->comment)
                                    <p class="mb-2 text-sm">– {{ $comment->comment }}</p>
                                @endif
                            @empty
                            -
                            @endforelse
                        </div>--}}
                        
                        <hr class="my-4">

                        <div>
                            <h3 class="mb-1 text-xs">People</h3>
                            <div class="mb-2">
                                <select name="people[]" class="w-full slim" multiple>
                                    <option value="">-</option>
                                    @foreach ($people as $person)
                                        <option value="{{ $person->id }}" {{ $image->people->contains($person->id) ? 'selected' : '' }}>{{ $person->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{--<h3 class="mb-1 text-xs">Dates</h3>
                            <div class="flex mb-2">
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
                            </div>--}}
                            <h3 class="mb-1 text-xs">Locations</h3>
                            <div class="flex mb-2">
                                <select name="location_id" class="w-full slim">
                                    <option value="">-</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}" {{ ($image->location && $image->location->id == $location->id) ? 'selected' : '' }}>{{ $location->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>
            
            </div>
        </div>
    </form>
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