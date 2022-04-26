@extends('base')

@section('content')
<div class="bg-gray-100 md:min-h-screen">
    <div class="flex flex-col md:flex-row" id="searchable-list" >
        <div class="md:fixed bg-black md:h-screen md:w-1/2 {{ !in_array($display, ['map', 'timeline']) ? 'p-4 md:overflow-y-auto' : 'overflow-hidden' }}">
            <div>
                @if(!in_array($display, ['map', 'timeline']))
                <section class="my-10 print-hidden">
                    <div class="flex gap-6 items-center">
            
                        <input type="text" name="query" placeholder="Search collection"
                            class="search hidden md:inline-block py-2 px-6 w-2/3 border border-gray-700 rounded-full focus:outline-none text-lg z-10">
                        
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

        <div class="md:fixed md:left-1/2 h-screen md:w-1/2 md:pr-16 bg-white md:overflow-y-auto">
            @include('collections.partials.collection-info')

            <div class="flex flex-col md:flex-row justify-between md:fixed bottom-0 md:left-1/2 md:w-1/2 p-4 pb-20 md:pr-20 md:pb-4 border-t leading-10 border-gray-700 bg-white">
                <a href="/?cid={{ $collection->id }}" class="hover:underline">Edit selection</a>

                <a href="{{ route('collections.edit', [$collection]) }}" class="hover:underline">Edit info</a>

                <form id="images-form" class="hidden" method="POST" enctype="multipart/form-data" action="{{ route('collections.uploadImage', [$collection]) }}">
                    @csrf
                    <input id="images-upload" onchange="document.querySelector('#images-form').submit()" class="hidden" type="file" name="images[]" accept="image/*" required multiple>
                </form>

                <button type="button" onclick="document.querySelector('#images-upload').click()" title="Add images to collection" class="hover:underline text-left">
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
        
        <livewire:collections-aside />
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
