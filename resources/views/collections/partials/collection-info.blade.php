<div class="p-14">
    <div class="relative flex items-center justify-between mb-12 ">
        <span class="block w-10 h-10 leading-10 rounded-full border border-gray-500 text-center text-xs">
            {{ $collection->images()->count() }}
        </span>
        <h2 class="text-4xl text-center">
            {{ $collection->label }}
        </h2>
        <span class="inline-block w-10 h-10 leading-10 border border-gray-500 text-center text-xs">
            {{ ($collection->maps()->count() ?? 0) + ($collection->docs()->count() ?? 0) + ($collection->documents()->count() ?? 0) }}
        </span>
    </div>

    @php
    $image = $collection->images[rand(0, $collection->images()->count()-1)];
    @endphp
    @if($collection->images->count() && $image)
    <div class="h-96 bg-center bg-cover mb-10"
        style="background-image: url('https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/960,/0/default.jpg')">
    </div>
    @endif

    @if($collection->description)
    <div class="w-full mb-10">
        {!! $collection->description !!}
    </div>
    @endif

    <div class="mb-10">
        <span class="text-xs">Download </span>
        <x-links.bare label="JSON" href="{{ env('API_URL') }}collections/{{ $collection->id }}" target="_blank"
            class="mr-2 text-xs underline" />
        <x-links.bare label="CSV" :href="route('collections.export', ['id' => $collection->id])" />
    </div>

    <div class="flex mb-10">
        <div class="w-1/3">
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
            </div>
            <form class="inline-block" x-data x-ref="documentsupload" method="POST" enctype="multipart/form-data"
                action="{{ route('collections.uploadDocuments', [$collection]) }}">
                @csrf
                <input x-ref="documents" @change="$refs.documentsupload.submit()" class="hidden" type="file"
                    name="documents[]" required multiple>
                <x-buttons.default @click="$refs.documents.click()" label="Upload documents" />
            </form>
        </div>
        <div class="w-1/3">
            <h2 class="text-xs mb-2">Notes</h2>
            <div>
                <ul>
                    @foreach ($collection->docs as $doc)
                    <li class="mb-2">
                        <x-links.default :label="$doc->label" href="{{ route('docs.edit', [$doc]) }}" />
                    </li>
                    @endforeach
                </ul>

                <form action="{{ route('docs.store') }}" method="post" class="inline-block">
                    @csrf

                    <input type="hidden" name="collections" value="{{ $collection->id }}">
                    <input type="hidden" name="label" value="{{ $collection->label }}">

                    <x-buttons.default label="New Note" type="submit" />
                </form>
            </div>
        </div>
        <div class="w-1/3">
            <h2 class="text-xs mb-2">Maps</h2>
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
