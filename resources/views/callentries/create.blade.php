@extends('base')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="flex flex-col md:flex-row" id="searchable-list" >
        <div class="hidden md:block fixed h-screen w-1/2 overflow-hidden">
        </div>

        <div class="md:fixed md:left-1/2 md:h-screen md:full md:w-1/2 md:pr-36 bg-white overflow-y-auto">
            <div class="p-4 md:p-14">

                @if(isset($image))
                    <h2 class="text-2xl mb-2">{{ $image->title }}</h2>
                    <img class="inline-block mb-4" 
                        src="https://sipi.participatory-archives.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/360,/0/default.jpg"
                        alt="{{$image->title}}" title="{{$image->title}}">
                @endif

                <form action="{{ route('callentries.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="call_id" value="{{ $call_id }}"/>

                    @if(isset($image))
                        <input type="hidden" name="image_id" value="{{ $image->id }}"/>
                    @endif

                    <div class="">
                        <label for="label" class="mb-1 text-xs">Title</label>
                        <div class="mb-2">
                            <input type="text" name="label" class="w-full border border-gray-300 p-1 px-2" placeholder="Give a title to your entry" required>
                        </div>
                    </div>

                    <div class="my-6">
                        <label class="mb-1 text-xs">Creator</label>
                        <div class="mb-2">
                            <input type="text" name="creator" class="w-full border border-gray-300 p-1 px-2" placeholder="Who are you" required>
                        </div>
                    </div>

                    <div class="my-6">
                        <label for="comment" class="mb-1 text-xs">Comment</label>
                        <div class="mb-2">
                            <textarea name="comment" placeholder="Comment your entry" class="w-full mt-1 border border-gray-300 p-1 px-2"></textarea>
                        </div>
                    </div>

                    <div class="my-6">
                        <label class="mb-1 text-xs">Documents, such as photos or textfiles</label>
                        <div class="mb-2">
                            <input type="file" name="documents[]" class="w-full border border-gray-300 p-1 px-2" placeholder="Attach some documents, like photos or textfiles to your entry." multiple>
                        </div>
                    </div>

                    <div class="my-6">
                        <h3 class="mb-1 text-xs">Keywords</h3>
                        <div class="mb-2">
                            <select name="keywords[]" class="w-full slim" multiple>
                                <option value="">-</option>
                                @foreach ($keywords as $keyword)
                                    <option value="{{ $keyword->id }}">{{ $keyword->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
        
                    <div class="flex flex-col md:flex-row justify-between md:fixed bottom-0 md:left-1/2 md:w-1/2 md:p-4 pb-20 md:pr-20 md:pb-4 border-t leading-10 border-gray-700 bg-white">
                        <button type="submit" class="hover:underline text-left">Save entry</button>
                    </div>

                </form>
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
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            let creator = document.querySelector('[name="creator"]');

            if(creator.value == '' && localStorage.getItem('call_entry_creator') != '') {
                creator.value = localStorage.getItem('call_entry_creator');
            }

            creator.addEventListener('change', () => {
                localStorage.setItem('call_entry_creator', creator.value);
            })

            document.querySelectorAll('select.slim').forEach(el => {
                console.log(el)
                new SlimSelect({
                    select: el,
                });
            });
        })

    </script>
@endsection
