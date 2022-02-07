@extends('base')

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex" id="searchable-list" >
        <div class="fixed h-screen w-1/2 {{ $display != 'map' ? 'p-4 overflow-y-auto' : 'overflow-hidden' }}">
            <div>
                @if($display != 'map')
                <section class="my-10 print-hidden">
                    <div class="flex gap-6 items-center">
            
                        <input type="text" name="query" placeholder="Search collection"
                            class="search py-2 px-6 w-2/3 border border-gray-700 rounded-full focus:outline-none text-lg z-10">
                        
                        @if($display != 'list')
                        <input type="range" min="1" max="6" x-model="cols">
                        @endif

                        @include('collections.partials.image-actions')
                </section>
                @endif
            
                <main>
                    <div id="images" class="pb-20">
                        @if($display == 'list')
                            @include('collections.partials.display-list')
                        @elseif($display == 'map')
                            @include('collections.partials.display-map')
                        @else
                            @include('collections.partials.display-grid')
                        @endif
                    </div>
                </main>
            </div>
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            <div class="pt-14 pb-20 pl-14 pr-4">
                <div class="flex items-center justify-between mb-12 ">
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
                    $image = $collection->images[rand(0, $collection->images()->count())];
                @endphp
                <div class="h-96 bg-center bg-cover mb-10" style="background-image: url('https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/960,/0/default.jpg')">
                </div>

                @if($collection->description)
                <div class="w-full mb-10">
                    {!! $collection->description !!}
                </div>
                @endif

                <div class="mb-10">
                    <span class="text-xs">Download </span>
                    <x-links.bare label="JSON" href="{{ env('API_URL') }}collections/{{ $collection->id }}" target="_blank" class="mr-2 text-xs underline"/>
                    <x-links.bare label="CSV" :href="route('collections.export', ['id' => $collection->id])"/>
                </div>
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

                <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-700 bg-white">
                    <a href="/?collection={{ $collection->id }}" class="hover:underline">Edit selection</a>

                    <a href="javascript:;" class="hover:underline">Edit info</a>
    
                    <form class="hidden" x-ref="imageupload" method="POST" enctype="multipart/form-data" action="{{ route('collections.uploadImage', [$collection]) }}">
                        @csrf
                        <input x-ref="images" @change="$refs.imageupload.submit()" class="hidden" type="file" name="images[]" accept="image/*" required multiple>
                    </form>

                    <button type="button" @click="$refs.images.click()" title="Add images to collection" class="hover:underline">
                        Add images
                    </button>

                    <a href="{{ route('collections.copy', [$collection]) }}" title="Copy images to new collection" class="hover:underline">
                        Copy images
                    </a>

                    @if($collection->origin != 'salsah')
                    <form action="{{ route('collections.destroy', [$collection]) }}" method="post" class="inline-block">
                        @csrf
                        @method('delete')
    
                        <x-buttons.delete/>
                    </form>
                    @endif
                </div>
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
    <script src="{{ asset('node_modules/list.js/dist/list.min.js') }}"></script>
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            var searchable_list = new List('searchable-list', {
                valueNames: ['title', 'signature', 'oldnr', 'tags']
            });
        });

    </script>
@endsection