@extends('base')

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex" id="searchable-list" >
        <div class="fixed bg-black h-screen w-1/2 p-4 overflow-y-auto">
            <div>
                <section class="my-10 print-hidden">
                    <div class="flex gap-6 items-center">
            
                        <input type="text" name="query" placeholder="Search collection"
                            class="search py-2 px-6 w-2/3 border border-gray-700 rounded-full focus:outline-none text-lg z-10">
                        
                        <input type="range" min="1" max="6" x-model="cols">
                </section>
            
                <main>
                    <div id="images" class="pb-20">
                        @include('collections.partials.display-grid', ['images' => $collection->images])
                    </div>
                </main>
            </div>
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            <form method="POST" action="{{ route('collections.update', [$collection]) }}">
                @csrf
                @method('patch')

                <div class="p-14 pr-0">
                    <div class="mb-12">
                        <label for="label" class="block">Label</label>
                        <input name="label" class="text-4xl border border-gray-500 p-2 w-full" value="{{ $collection->label ?? '' }}" placeholder="Label"/>
                    </div>
                    <div class="w-full">
                        <label for="description" class="block">Description</label>
                        <textarea name="description" id="description" class="border border-gray-500 p-2 w-full" oninput="auto_grow(this)">{{ $collection->description ?? '' }}</textarea>
                    </div>
                </div>

                <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-700 bg-white">
                    <button type="submit" class="hover:underline">Save info</button>
                </div>
            </form>
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
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            auto_grow(document.querySelector('#description'));
        });

        function auto_grow(element) {
            element.style.height = "5px";
            element.style.height = (element.scrollHeight)+"px";
        }

    </script>
@endsection
        