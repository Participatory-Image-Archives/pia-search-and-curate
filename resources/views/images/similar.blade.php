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

                            <div class="flex gap-2 items-center print-hidden" x-data>
                                <a class="text-xs mr-1 ml-2 underline text-white" href="{{ route('images.similar', [$image]) }}?category=localcolor">localcolor</a>
                                <a class="text-xs mr-1 underline text-white" href="{{ route('images.similar', [$image]) }}?category=globalcolor">globalcolor</a>
                                <a class="text-xs mr-1 underline text-white" href="{{ route('images.similar', [$image]) }}?category=localfeatures">localfeatures</a>
                            </a>
                    </div>
                </section>
            
                <main>
                    <div id="images" class="pb-20">
                        @include('collections.partials.display-grid', ['images' => $images])
                    </div>
                </main>
            </div>
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            @include('images.partials.details')
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