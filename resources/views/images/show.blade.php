@extends('base')

@section('content')
<div class="p-4">
    <div class="mb-4">
        <h2 class="text-2xl mb-2">{{ $image->title }}</h2>
    </div>
    <div class="flex">
        <div class="w-full md:w-1/2">
            @foreach ($image->collections as $c)
                @if ($c->origin == 'salsah')
                    <img class="inline-block mr-2 w-full shadow-2xl" src="https://pia-iiif.dhlab.unibas.ch/{{$c->signature}}/{{$image->signature}}.jp2/full/640,/0/default.jpg" alt="{{ $image->title }}" title="{{ $image->title }}">
                @endif
            @endforeach
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
                        <td>{{ $image->salsah_id ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Old Nr</td>
                        <td>{{ $image->oldnr ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Signature</td>
                        <td>{{ $image->signature ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Original Title</td>
                        <td>{{ $image->original_title ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Original File Name</td>
                        <td>{{ $image->original_file_name ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td>Sequence Number</td>
                        <td>{{ $image->sequence_number ?? '–' }}</td>
                    </tr>
                </table>

                <hr class="my-4">

                <table class="w-full">
                    <tr>
                        <td class="w-1/3">Verso</td>
                        <td>
                            @if ($image->verso)
                                <a href="{{ route('images.show', [$id => $image->verso]) }}">Verso</a>
                            @else
                            –
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Object Type</td>
                        <td>
                            {{ $image->objectType->label ?? '–' }} / 
                            {{ $image->objectType->comment ?? '–' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Model Type</td>
                        <td>
                            {{ $image->modelType->label ?? '–' }} / 
                            {{ $image->modelType->comment ?? '–' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Format</td>
                        <td>
                            {{ $image->format->label ?? '–' }} / 
                            {{ $image->format->comment ?? '–' }}
                        </td>
                    </tr>
                </table>
                
                <hr class="my-4">

                <div>
                    <h3 class="mb-1 text-xs">Keywords</h3>
                    <div class="flex mb-2">
                        @forelse ($image->keywords as $keyword)
                            @if ($keyword->label)
                                <a href="/?keyword={{ $keyword->id }}"
                                class="inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white mr-2 mb-2">{{ $keyword->label }}</a>
                            @endif
                        @empty
                        –
                        @endforelse
                    </div>
                    <h3 class="mb-1 text-xs">Collections</h3>
                    <div class="flex mb-2">
                        @forelse ($image->collections as $collection)
                            @if ($collection->label)
                                <a href="{{ route('collections.show', [$collection]) }}"
                                class="inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white mr-2 mb-2">{{ $collection->label }}</a>
                            @endif
                        @empty
                        –
                        @endforelse
                    </div>
                </div>
                
                <hr class="my-4">

                <div>
                    <h3 class="mb-1 text-xs">Comments</h3>
                    @forelse ($image->comments as $comment)
                        @if ($comment->comment)
                            <p class="mb-2 text-sm">– {{ $comment->comment }}</p>
                        @endif
                    @empty
                    -
                    @endforelse
                </div>
                
                <hr class="my-4">

                <div>
                    <h3 class="mb-1 text-xs">People</h3>
                    <div class="flex mb-2">
                        @forelse ($image->people as $person)
                            @if ($person->name)
                                <span class="inline-block py-1 px-3 text-xs rounded-full bg-black text-white mr-2 mb-2">{{ $person->label }}</span>
                            @endif
                        @empty
                        –
                        @endforelse
                    </div>
                    <h3 class="mb-1 text-xs">Dates</h3>
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
                    </div>
                    <h3 class="mb-1 text-xs">Locations</h3>
                    <div class="flex mb-2">
                        @if ($image->location)
                            @if ($image->location->label)
                                <span class="inline-block py-1 px-3 text-xs rounded-full bg-black text-white mr-2 mb-2">{{ $image->location->label }}</span>
                            @endif
                        @else
                        -
                        @endforelse
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div>
                    <h3 class="mb-1 text-xs">Links</h3>
                    <ul class="text-sm">
                        @foreach ($image->collections as $c)
                            @if ($c->origin == 'salsah')
                                <li>– <a target="_blank" class="underline" href="https://pia-iiif.dhlab.unibas.ch/{{$c->signature}}/{{$image->signature}}.jp2/full/max,/0/default.jpg">IIIF</a> <a target="_blank" class="underline" href="https://pia-iiif.dhlab.unibas.ch/{{$c->signature}}/{{$image->signature}}.jp2/">Manifest</a></li>
                            @endif
                        @endforeach
                        <li>– <a target="_blank" class="underline" href="{{ env('API_URL') }}images/{{ $image->id }}">JSON</a></li>
                        <li>– <a target="_blank" class="underline" href="https://data.dasch.swiss/resources/{{ $image->salsah_id }}">SALSAH</a></li>
                    </ul>
                </div>
            </div>
        
        </div>
    </div>
</div>
@endsection