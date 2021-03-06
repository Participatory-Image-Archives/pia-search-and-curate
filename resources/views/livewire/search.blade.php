<div>
    <div wire:loading>
        <div class="fixed top-0 left-0 h-full w-full bg-gray-100 bg-opacity-75 flex justify-around items-center z-50">
            <span class="text-4xl">Loading…</span>
        </div>
    </div>

    <div
        class="modal-wrap hidden fixed top-0 left-0 w-screen h-screen flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
        <div class="modal-map inline-block bg-white p-4 rounded-xl">
            <iframe src="{{ route('search.byCoordinates') }}" frameborder="0" width="400px" height="450px"></iframe>
        </div>
    </div>

    <section class="my-10">
        <form wire:submit.prevent="update">
            <div class="flex md:justify-center">
                <input type="text" name="query" wire:model.defer="query" autocomplete="new-password" value="{{ $query }}"
                class="py-2 px-6 w-2/3 md:w-1/3 border border-gray-700 rounded-full focus:outline-none text-lg z-10">
            <button type="submit"
                class="relative -left-5 text-lg z-0 border border-gray-700 bg-white hover:bg-gray-700 hover:text-white pl-8 pr-6">Search</button>
            </div>

            <div class="hidden md:flex justify-center mt-4">
                <div class="flex border border-gray-700 rounded-full px-4 text-xs">
                    <label for="from" class="py-2 pr-1">Search by date from</label>
                    <input type="date" name="from" wire:model.defer="from" autocomplete="new-password" value="{{ $from }}" class="p-1 m-1">
                    <label for="to" class="py-2 px-1">to</label>
                    <input type="date" name="to" wire:model.defer="to" autocomplete="new-password" value="{{ $to }}" class="p-1 m-1">
                    <button type="button" class="text-xs underline ml-2" wire:click.defer="clear_dates()">clear</button>
                </div>
                <div class="flex border border-gray-700 rounded-full px-4 ml-2 text-xs">
                    <a href="{{ route('search.byCoordinates') }}" onclick="event.preventDefault(); document.querySelector('.modal-wrap').classList.remove('hidden')" class="block leading-8">Search by Map</a>
                    <button type="button" class="text-xs underline ml-2" wire:click.defer="clear_coordinates()">clear</button>
                </div>
            </div>
        </form>
    </section>
    
    <div>
        <div class="p-0 md:p-10 md:pr-32 pb-20 space-y-4" x-data>
            <main>
                @if($pagination ?? false && $images->total() > 50)
                <div class="py-4">
                    {{ $images->links() }}
                </div>
                @endif
                @if($iotd ?? false)
                <h2 class="text-2xl mb-4 font-bold">Images of the moment</h2>
                <div class="grid gap-4 grid-flow-row grid-cols-1 lg:grid-cols-2 xl:grid-cols-3">
                @else
                <div class="grid gap-4 grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
                @endif
                    @foreach ($images as $image)
                        <div wire:key="image_{{ $image->id }}" class="image bg-gray-100 relative"
                            style="height: auto; min-height: {{ ($iotd ?? false) ? 'auto' : '200px' }};">
                            <img class="w-full" loading="lazy"
                                src="https://sipi.participatory-archives.ch/{{ $image->base_path }}/{{ $image->signature }}.jp2/full/{{ ($iotd ?? false) ? '560' : '320' }},/0/default.jpg"
                                alt="{{ $image->title }}" title="{{ $image->title }}"/>
                            <div
                                class="meta hidden absolute top-0 left-0 p-2 w-full text-right">
                                <a
                                    href="{{ route('images.show', $image->id) }}"
                                    target="_blank"
                                    class="rounded-full bg-blue-500 text-white text-xs py-1 px-3">Details</a>
                                <button
                                    class="rounded-full bg-white hover:bg-black hover:text-white text-xs py-1 px-3"
                                    type="button" wire:click="select({{ $image->id }})">+ Add</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </main>
        </div>
    </div>

    <div id="selection-wrapper"
        class="flex fixed top-0 right-0 transform transition min-h-screen shadow-2xl z-20">
        
        <div id="selection"
            class="hidden md:block min-h-screen max-h-screen w-80 bg-black p-4 overflow-y-auto overflow-x-hidden">
            <div>
                <div id="collection">
                    <form action="{{ route('collections.store') }}" method="post" x-ref="collection_form">
                        @csrf
                        <input type="hidden" name="image_ids" wire:model="image_ids">
                        
                        <div class="flex">
                            <input type="text" name="label" class="py-2 px-6 w-56 focus:outline-none text-lg z-10 bg-white text-black rounded-full" placeholder="Label" value="{{ $collection->label ?? '' }}" required>
                            <button type="submit" class="relative -left-5 text-lg z-0 pl-7 pr-3 border border-white text-white hover:bg-white hover:text-black">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            </button>
                        </div>
                        
                        @if(isset($collection))
                        <input type="hidden" name="collection_id" value="{{ $collection->id }}">
                        <!--<button type="button" class="p-2 px-4 text-white text-xs underline"
                            click.prevent="() => {
                                collection.id = '';
                                setTimeout(() => {
                                    $refs.collection_form.submit();
                                }, 100);
                            }">Add as new Collection</button>-->
                        @endif
                        
                    </form>
                </div>
                <div class="my-2">
                    @if($images->count() < 100)
                    <button type="button" wire:click.defer="add_all_results()"
                    class="py-1 px-4 rounded-full border border-white text-white hover:bg-green-500 hover:text-white text-xs">Add all search results to selection</button>
                    @endif
                    @if(count($selection))
                    <button wire:click="delete_selection()" type="button"
                        class="py-1 px-4 mt-2 rounded-full border border-white text-white hover:bg-red-500 hover:text-white text-xs">Delete Selection</button>
                    @endif
                </div>
                <div>
                    @foreach ($selection as $image)
                        <div class="mt-4 cursor-not-allowed" wire:key="selected_{{ $image['id'] }}"
                            wire:click.defer="forget({{ $image['id'] }})">
                            <img class="w-full"
                                src="https://sipi.participatory-archives.ch/{{ $image['base_path'] }}/{{ $image['signature'] }}.jp2/full/280,/0/default.jpg"
                                alt="{{ $image['title'] }}" title="{{ $image['title'] }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <aside id="sidebar">
        <livewire:collections-aside />
    </aside>
    
</div>
