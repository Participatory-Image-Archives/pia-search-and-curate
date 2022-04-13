@extends('base')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="flex" id="searchable-list" >
        <div class="fixed h-screen w-1/2 overflow-hidden">
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            <div class="pt-14 pb-20 pl-14 pr-4">

                <form action="{{ route('calls.update', [$call]) }}" method="post">
                    @csrf
                    @method('patch')

                    <div class="relative flex items-center justify-between mb-12 ">
                        <h2 class="text-4xl text-center">
                            <input type="text" name="label" value="{{ $call->label ?? '' }}" class="w-full border border-gray-300 p-1 px-2">
                        </h2>
                    </div>

                    <div class="my-6">
                        <h3 class="mb-1 text-xs">Description</h3>
                        <div class="mb-2">
                            <textarea name="description" placeholder="Description of call" class="w-full mt-1 border border-gray-300 p-1 px-2">{{ $call->description ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="my-6">
                        <h3 class="mb-1 text-xs">Creator</h3>
                        <div class="mb-2">
                        <input type="text" name="creator" value="{{ $call->creator ?? '' }}" class="w-full border border-gray-300 p-1 px-2" placeholder="Who initiated this call">
                        </div>
                    </div>

                    <div class="my-6">
                        <h3 class="mb-1 text-xs">Start Date</h3>
                        <div class="mb-2">
                        <input type="date" name="start_date" value="{{ $call->start_date ?? '' }}" class="w-full border border-gray-300 p-1 px-2">
                        </div>
                    </div>

                    <div class="my-6">
                        <h3 class="mb-1 text-xs">End Date</h3>
                        <div class="mb-2">
                        <input type="date" name="end_date" value="{{ $call->end_date ?? '' }}" class="w-full border border-gray-300 p-1 px-2">
                        </div>
                    </div>

                    <div class="my-6">
                        <h3 class="mb-1 text-xs">Keywords</h3>
                        <div class="mb-2">
                            <select name="keywords[]" class="w-full slim" multiple>
                                <option value="">-</option>
                                @foreach ($keywords as $keyword)
                                    <option value="{{ $keyword->id }}" {{ $call->keywords->contains($keyword->id) ? 'selected' : '' }}>{{ $keyword->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
        
                    <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-300 bg-white">
                        <button type="submit" class="hover:underline">Save info</button>
                        <a href="{{ route('collections.show', [$call->collection_id]) }}">View collection</a>
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

            if(creator.value == '' && localStorage.getItem('call_creator') != '') {
                creator.value = localStorage.getItem('call_creator');
            }

            creator.addEventListener('change', () => {
                localStorage.setItem('call_creator', creator.value);
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
