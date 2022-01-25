@extends('base')

@section('content')
<div class="p-4">
    <div class="md:flex mb-4">
        <div class="md:w-1/2">
            <h2 class="text-2xl mb-2">
                {{ $collection->label }}
            </h2>
            <div class="print-hidden">
                <x-links.cta label="Edit" href="/?collection={{ $collection->id }}"/>

                @if($collection->origin != 'salsah')
                <form action="{{ route('collections.destroy', [$collection]) }}" method="post" class="inline-block">
                    @csrf
                    @method('delete')

                    <x-buttons.delete/>
                </form>
                @endif

                <span class="text-xs ml-2 mr-1">Download </span>
                <x-links.bare label="JSON" href="{{ env('API_URL') }}collections/{{ $collection->id }}" target="_blank" class="mr-2 text-xs underline"/>
                <x-links.bare label="CSV" :href="route('collections.export', ['id' => $collection->id])"/>
            </div>
        </div>
        <div class="md:w-1/2 md:text-right print-hidden">
            @include('partials.lists-dropdown')
            <x-links.default label="Home" href="/"/>
        </div>
    </div>
    <div class="w-full mb-10">
        {!! $collection->description !!}
    </div>
    <div class="flex">
        <div class="w-1/4 pr-4 print-hidden">
            <div class="mb-10">
                <h2 class="text-xs mb-2">Documents</h2>
                <div>
                    <ul>
                    @foreach ($collection->documents as $document)
                        <li class="mb-2"><x-links.default :label="$document->label" href="/{{ 'storage/' . $document->base_path . '/' . $document->file_name }}"/></li>
                    @endforeach
                    </ul>
                </div>
                <form class="inline-block" x-data x-ref="documentsupload" method="POST" enctype="multipart/form-data" action="{{ route('collections.uploadDocuments', [$collection]) }}">
                    @csrf
                    <input x-ref="documents" @change="$refs.documentsupload.submit()" class="hidden" type="file" name="documents[]" required multiple>
                    <x-buttons.default @click="$refs.documents.click()" label="Upload documents"/>
                </form>
            </div>
            <div class="mb-10">
                <h2 class="text-xs mb-2">Notes</h2>
                <div>
                    <ul>
                    @foreach ($collection->docs as $doc)
                        <li class="mb-2"><x-links.default :label="$doc->label" href="{{ env('DOCS_URL') }}/{{ $doc->id }}/edit"/></li>
                    @endforeach
                    </ul>
    
                    <form action="{{ env('DOCS_URL') }}/create" method="get" class="inline-block">
                        @csrf
                        <input type="hidden" name="collections" value="{{ $collection->id }}">
                        <input type="hidden" name="label" value="{{ $collection->label }}">
                        
                        <x-buttons.default label="New Note" type="submit"/>
                    </form>
                </div>
            </div>
            <div class="">
                <h2 class="text-xs mb-2">Maps</h2>
                <div>
                    <ul>
                    @foreach ($collection->maps as $map)
                        <li class="mb-2"><x-links.default :label="$map->label" href="{{ env('MAPS_URL') }}/{{ $map->id }}"/></li>
                    @endforeach
                    </ul>
    
                    <form action="{{ env('MAPS_URL') }}/create" method="get" class="inline-block">
                        @csrf
                        <input type="hidden" name="collections" value="{{ $collection->id }}">
                        <input type="hidden" name="label" value="{{ $collection->label }}">
                        
                        <x-buttons.default label="New Map" type="submit"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="w-3/4">
            @include('collections.image-actions')
            <div class="grid gap-4 {{ $display == 'list' ? '' : 'grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6' }} print-grid print-w-full">
                @foreach ($collection->images as $image)
                    @if($display == 'list')
                    <div class="flex">
                        <a href="{{ route('images.show', [$image]) }}" class="pr-2 w-1/4 print-image">
                            <img class="inline-block" src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/360,/0/default.jpg" alt="{{$image->title}}" title="{{$image->title}}">
                        </a>
                        <div class="image-meta w-3/4 pl-2">
                            <table class="w-full mb-4">
                                <thead class="text-xs">
                                    <tr>
                                        <td class="pb-2 w-1/5">Field</td>
                                        <td class="pb-2">Value</td>
                                    </tr>
                                </thead>
                                <tr>
                                    <td>Title</td>
                                    <td>{{ $image->title ?? '–' }}</td>
                                </tr>
                                <tr>
                                    <td>Signature</td>
                                    <td>{{ $image->signature ?? '–' }}</td>
                                </tr>
                                <tr>
                                    <td>Old Nr</td>
                                    <td>{{ $image->oldnr ?? '–' }}</td>
                                </tr>
                            </table>
                            <div class="inline-block w-full">
                                <h3 class="mb-1 text-xs">Keywords</h3>
                                <div class="mb-2">
                                    @forelse ($image->keywords as $keyword)
                                        @if ($keyword->label)
                                            <x-links.default href="/?keyword={{ $keyword->id }}" :label="$keyword->label" class="mb-2"/>
                                        @endif
                                    @empty
                                    –
                                    @endforelse
                                </div>
                                <h3 class="mb-1 text-xs">Collections</h3>
                                <div class="mb-2">
                                    @forelse ($image->collections as $collection)
                                        @if ($collection->label)
                                            <x-links.default :href="route('collections.show', [$collection])" :label="$collection->label" class="mb-2"/>
                                        @endif
                                    @empty
                                    –
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('images.show', [$image]) }}" class="print-image">
                        <img class="inline-block mr-2 w-full" src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/360,/0/default.jpg" alt="{{$image->title}}" title="{{$image->title}}">
                        <div class="print-image-meta p-2">
                            <span class="text-xs">{{ $image->title }}</span>
                        </div>
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection