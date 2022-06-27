<div class="p-4 md:p-14">
    <div class="relative flex items-center justify-between mb-12 ">
        <span class="block w-10 h-10 leading-10 rounded-full border border-gray-500 text-center text-xs">
            {{ $collection->images()->count() }}
        </span>
        <h2 class="text-4xl text-center">
            {{ $collection->label }}
        </h2>
        <span class="inline-block w-10 h-10 leading-10 border border-gray-500 text-center text-xs">
            {{ ($collection->maps()->count() ?? 0) + ($collection->notes()->count() ?? 0) + ($collection->documents()->count() ?? 0) }}
        </span>
    </div>

    @php
    if($collection->images()->count()) {
        $image = $collection->images[rand(0, $collection->images()->count()-1)];
    }
    @endphp
    @if(isset($image))
    <div class="h-96 bg-center bg-cover mb-10"
        style="background-image: url('https://sipi.participatory-archives.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/960,/0/default.jpg')">
    </div>
    @endif

    @if($collection->description)
    <div class="w-full mb-2">
        {!! nl2br($collection->description) !!}
    </div>
    @endif

    @if($collection->keywords->count())
    <div class="w-full mb-2">
        @foreach ($collection->keywords as $keyword)
            @if ($keyword->label)
                <x-links.default :href="route('keywords.show', [$keyword])" :label="$keyword->label" class="mb-2"/>
            @endif
        @endforeach
    </div>
    @endif

    
    <div class="w-full mb-2 text-xs">
        <em>
            @if($collection->creator)
            Created by {{ $collection->creator }},
            @else
            Created on
            @endif
            {{ date('d. M Y', strtotime($collection->created_at)) }}<br>Updated on {{ date('d. M Y', strtotime($collection->updated_at)) }}
        </em>
    </div>

    <hr class="my-10">

    <div class="mb-10">
        <span class="text-xs">View </span>
        <ul class="list">
            <li>
                &mdash; 
                <span class="text-xs">IIIF Presentation API: </span>
                <x-links.bare label="manifest.json" href="https://iiif.participatory-archives.ch/collections/{{$collection->id}}.json" target="_blank"/>
            </li>
            <li>&mdash; <x-links.bare label="JSON" href="{{ env('API_URL') }}collections/{{ $collection->id }}" target="_blank"
                class="mr-2 text-xs underline" /></li>
            <li>&mdash; <x-links.bare label="CSV" :href="route('collections.export', ['id' => $collection->id])" /></li>
        </ul>
    </div>

    <div class="flex flex-col md:flex-row mb-10">
        <div class="w-full md:w-1/2">
            <h2 class="text-xs mb-2">Documents</h2>
            <div>
                <ul>
                    @foreach ($collection->documents as $document)
                    <li class="mb-2">
                        <x-links.default :label="$document->label"
                            href="/{{ 'storage/' . $document->base_path . '/' . $document->file_name }}" />
                    </li>
                    @endforeach
                </ul>
                <form class="inline-block" x-data x-ref="documentsupload" method="POST" enctype="multipart/form-data"
                    action="{{ route('collections.uploadDocuments', [$collection]) }}">
                    @csrf
                    <input x-ref="documents" @change="$refs.documentsupload.submit()" class="hidden" type="file"
                        name="documents[]" required multiple>
                    <x-buttons.default @click="$refs.documents.click()" label="Upload documents" />
                </form>
            </div>
            <h2 class="text-xs mb-2 mt-4">Calls</h2>
            <div>
                <ul>
                    @foreach ($collection->calls as $call)
                    <li class="mb-2">
                        <x-links.default :label="$call->label" href="{{ route('calls.show', [$call]) }}" />
                    </li>
                    @endforeach
                </ul>

                <form action="{{ route('calls.store') }}" method="post" class="inline-block">
                    @csrf

                    <input type="hidden" name="collection_id" value="{{ $collection->id }}">

                    <x-buttons.default label="New Call" type="submit" />
                </form>
            </div>
        </div>
        <div class="w-full md:w-1/2">
            <h2 class="text-xs mt-4 md:mt-0 mb-2">Notes</h2>
            <div>
                <ul>
                    @foreach ($collection->notes as $note)
                    <li class="mb-2">
                        <x-links.default :label="$note->label" href="{{ route('notes.edit', [$note]) }}" />
                    </li>
                    @endforeach
                </ul>

                <form action="{{ route('notes.store') }}" method="post" class="inline-block">
                    @csrf

                    <input type="hidden" name="collections" value="{{ $collection->id }}">
                    <input type="hidden" name="label" value="{{ $collection->label }}">

                    <x-buttons.default label="New Note" type="submit" />
                </form>
            </div>
            <h2 class="text-xs mb-2 mt-4">Maps</h2>
            <div>
                <ul>
                    @foreach ($collection->maps as $map)
                    <li class="mb-2">
                        <x-links.default :label="$map->label" href="{{ route('maps.images', [$map]) }}" />
                    </li>
                    @endforeach
                </ul>

                <form action="{{ route('maps.store') }}" method="post" class="inline-block">
                    @csrf

                    <input type="hidden" name="collections" value="{{ $collection->id }}">
                    <input type="hidden" name="label" value="{{ $collection->label }}">

                    <x-buttons.default label="New Map" type="submit" />
                </form>
            </div>
        </div>

    </div>
</div>
