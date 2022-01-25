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
            <div class="flex items-center print-hidden mb-2" x-data>
                <h2 class="text-xs mr-2">Images</h2>
                <a href="{{ route('collections.show', [$collection]) }}" class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                      </svg>
                </a>
                <a href="{{ route('collections.show', [$collection]) }}?display=list" class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </a>
                <a href="{{ route('collections.map', [$collection]) }}" class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </a>
                <form x-ref="imageupload" method="POST" enctype="multipart/form-data" action="{{ route('collections.uploadImage', [$collection]) }}">
                    @csrf
                    <input x-ref="images" @change="$refs.imageupload.submit()" class="hidden" type="file" name="images[]" accept="image/*" required multiple>
                </form>

                <button type="button" @click="$refs.images.click()" title="Add images to collection" class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </button>

                <a href="{{ route('collections.copy', [$collection]) }}" title="Copy images to new collection" class="mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </a>
                
            </div>
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