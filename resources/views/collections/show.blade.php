@extends('base')

@section('content')
<div id="searchable-list" class="p-4 bg-gray-100 min-h-screen" x-data="app">

    <div class="fixed top-0 left-0 h-full w-full bg-gray-100 bg-opacity-75 flex justify-around items-center z-50" x-show="loading">
        <span class="font-bold text-white text-8xl">Loading…</span>
    </div>

    <section class="my-10 print-hidden">
        <div class="flex justify-center">

            <input type="text" name="query"
                class="search py-2 px-6 w-1/3 border border-gray-700 rounded-full focus:outline-none text-lg z-10">
            <button type="submit"
                class="relative -left-5 text-lg z-0 border border-gray-700 bg-white hover:bg-gray-700 hover:text-white pl-8 pr-6">Search</button>
        </div>
        <div class="flex justify-center mt-2">
            
        </div>
    </section>

    <main class="pr-20">
        @include('collections.image-actions')
        <div id="images" class="pb-20">
            <div class="list grid gap-4 {{ $display == 'list' ? '' : 'grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6' }} print-grid print-w-full">
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
                                    <td class="title">{{ $image->title ?? '–' }}</td>
                                </tr>
                                <tr>
                                    <td>Signature</td>
                                    <td class="signature">{{ $image->signature ?? '–' }}</td>
                                </tr>
                                <tr>
                                    <td>Old Nr</td>
                                    <td class="oldnr">{{ $image->oldnr ?? '–' }}</td>
                                </tr>
                            </table>
                            <div class="inline-block w-full">
                                <h3 class="mb-1 text-xs">Keywords</h3>
                                <div class="tags mb-2">
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
                            <span class="title text-xs">{{ $image->title }}</span>
                        </div>
                        <div class="hidden">
                            <span class="signature">{{ $image->signature ?? '–' }}</span>
                            <span class="oldnr">{{ $image->oldnr ?? '–' }}</span>
                            <span class="tags">
                                @foreach ($image->keywords as $keyword)
                                    @if ($keyword->label)
                                        {{ $keyword->label }}
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    </a>
                    @endif
                @endforeach
                </div>
        </div>
    </main>

    <aside id="sidebar"
        x-data="{translate: '80px', expand_collections: false}"
        @mouseover="translate = '100%'" @mouseleave="translate = '80px'; expand_collections = false;"
        class="flex fixed top-0 right-0 transform transition min-h-screen shadow-2xl z-50 print-hidden"
        :style="`transform: translateX(calc(100% - ${translate}))`">
        
        @include('frontend.partials.aside-collections')

        <div id="meta"
            class="min-h-screen max-h-screen w-96 p-4 bg-white shadow-2xl overflow-y-auto overflow-x-hidden">
            <div>
                <h2 class="text-2xl mb-2">
                    {{ $collection->label }}
                </h2>
                <div class="w-full mb-10">
                    {!! $collection->description !!}
                </div>
                <div>
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
                <div>
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
            </div>
        </div>
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