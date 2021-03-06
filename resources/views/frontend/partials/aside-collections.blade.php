<div id="collections"
    class="min-h-screen max-h-screen w-20 bg-white border-left border-gray-500 transition-all overflow-y-auto overflow-x-hidden"
    :style="expand_collections ? 'width: 640px;' : ''">

    <div>
        <div class="flex px-3 py-2 justify-between">
            <button @click="expand_collections = ! expand_collections">
                <span x-show="! expand_collections">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </span>
                <span x-show="expand_collections">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </span>
            </button>
            <a class="leading-10 hover:underline font-bold" href="{{ route('collections.index') }}" x-show="expand_collections">All Collections</a>
            <span class="inline-block w-10 h-10 leading-10">
            </span>
        </div>

        <ul>
        @foreach ($collections as $collection)
            <li class="flex justify-between px-3 py-2 border-b border-gray-400">
                <span
                    class="block w-10 h-10 leading-10 rounded-full border border-gray-500 text-center text-xs cursor-pointer"
                    @click="expand_collections = ! expand_collections">{{ $collection->images()->count() }}</span>
                <a class="leading-10 hover:underline" href="{{ route('collections.show', [$collection]) }}" x-show="expand_collections">{{ $collection->label }}</a>
                <span class="inline-block w-10 h-10 leading-10 border border-gray-500 text-center text-xs" x-show="expand_collections">
                    {{ $collection->maps()->count() + $collection->notes()->count() + $collection->documents()->count() }}
                </span>
            </li>
        @endforeach
        </ul>
    </div>
</div>