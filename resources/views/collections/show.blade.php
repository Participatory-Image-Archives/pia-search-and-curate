@extends('base')

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex" id="searchable-list" >
        <div class="fixed bg-black h-screen w-1/2 {{ !in_array($display, ['map', 'timeline']) ? 'p-4 overflow-y-auto' : 'overflow-hidden' }}">
            <div>
                @if(!in_array($display, ['map', 'timeline']))
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
                    <div id="images" class="{{ !in_array($display, ['map', 'timeline']) ? 'pb-20' : 'min-h-screen' }}">
                        @if($display == 'list')
                            @include('collections.partials.display-list')
                        @elseif($display == 'map')
                            @include('collections.partials.display-map')
                        @elseif($display == 'timeline')
                            @include('collections.partials.display-timeline')
                        @else
                            @include('collections.partials.display-grid', ['images' => $collection->images])
                        @endif
                    </div>
                </main>
            </div>
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            @include('collections.partials.collection-info')

            <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-700 bg-white">
                <a href="/?collection={{ $collection->id }}" class="hover:underline">Edit selection</a>

                <a href="{{ route('collections.edit', [$collection]) }}" class="hover:underline">Edit info</a>

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