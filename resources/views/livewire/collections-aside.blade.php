<div id="collections"
    class="fixed right-0 top-0 md:block min-h-screen max-h-screen bg-white border-left border-gray-500 overflow-y-auto overflow-x-hidden z-50 shadow-2xl">

    <div>
        <div class="flex px-3 py-2 justify-between">
            <button>
                <span>
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </span>
                <span class="hidden">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </span>
            </button>
            <a class="leading-10 hover:underline font-bold" href="{{ route('collections.index') }}">All Collections</a>
            <span class="inline-block w-10 h-10"></span>
        </div>

        <ul>
        @foreach ($collections as $collection)
            <li class="flex justify-between px-3 py-2 border-b border-gray-400">
                <span
                    class="block w-10 h-10 leading-10 rounded-full border border-gray-500 text-center text-xs cursor-pointer"
                    >{{ $collection->images()->count() }}</span>
                <a class="collection-label leading-10 hover:underline" href="{{ route('collections.show', [$collection]) }}">{{ $collection->label }}</a>
                <span class="inline-block w-10 h-10 leading-10 border border-gray-500 text-center text-xs">
                    {{ $collection->maps()->count() + $collection->notes()->count() + $collection->documents()->count() }}
                </span>
            </li>
        @endforeach
        </ul>
    </div>
</div>
