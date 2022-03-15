<div>
    <div wire:loading>
        <div class="fixed top-0 left-0 h-full w-full bg-gray-100 bg-opacity-75 flex justify-around items-center z-50">
            <span class="text-4xl">Loading…</span>
        </div>
    </div>

    <section class="my-10">
        <form wire:submit.prevent="update">
            <div class="flex justify-center">
                <input type="text" name="query" wire:model.defer="query" autocomplete="new-password" value="{{ $query }}"
                class="py-2 px-6 w-1/3 border border-gray-700 rounded-full focus:outline-none text-lg z-10">
            <button type="submit"
                class="relative -left-5 text-lg z-0 border border-gray-700 bg-white hover:bg-gray-700 hover:text-white pl-8 pr-6">Search</button>
            </div>

            <div class="flex justify-center mt-4">
                <label for="from" class="py-2 pr-1">Search or filter by date from</label>
                <input type="date" name="from" wire:model.defer="from" autocomplete="new-password" value="{{ $from }}" class="p-1 m-1">
                <label for="to" class="py-2 px-1">to</label>
                <input type="date" name="to" wire:model.defer="to" autocomplete="new-password" value="{{ $to }}" class="p-1 m-1">
            </div>
        </form>
        {{--<div class="flex justify-center mt-2">
            <label class="inline-block cursor-pointer" title="Search through title, signature and old number">
                <input type="radio" name="search_focus_choices" value="fuzzy"
                    x-model="search_focus" >
                Fuzzy
            <label class="inline-block cursor-pointer ml-6">
                <input type="radio" name="search_focus_choices" value="comments"
                    x-model="search_focus">
                Comments
            </label>
            <label class="inline-block ml-6">
                <input type="radio" name="search_focus_choices" value="coordinates"
                    x-model="search_focus">
                <a href="javascript:;" @click="modal_map = true">Map</a>
            </label>
            <label class="inline-block ml-6">
                <input type="radio" name="search_focus_choices" value="dates"
                    x-model="search_focus">
                <a href="javascript:;" @click="modal_dates = true">Dates</a>
            </label>
        </div>--}}
    </section>
    
    <div>
        <div class="p-10 pr-20 pb-20 space-y-4" x-data>
            <main>
                @if($images->total() > 50)
                <div class="py-4">
                    {{ $images->links() }}
                </div>
                @endif
                <div class="grid gap-4 grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
                    @foreach ($images as $image)
                        <div wire:key="image_{{ $image->id }}" class="bg-gray-100 relative"
                            x-data="{loaded: false, show_meta: false}"
                            style="height: 200px;"
                            :style="loaded ? '' : 'height: 200px;'"
                            @mouseover="show_meta = true" @mouseout="show_meta = false">
                            <img class="w-full"
                                style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;"
                                src="https://pia-iiif.dhlab.unibas.ch/{{ $image->base_path }}/{{ $image->signature }}.jp2/full/50,/0/default.jpg"
                                data-src="https://pia-iiif.dhlab.unibas.ch/{{ $image->base_path }}/{{ $image->signature }}.jp2/full/320,/0/default.jpg"
                                alt="{{ $image->title }}" title="{{ $image->title }}"
                                @load="loaded = true; observer.observe($el);">
                            <div x-show="show_meta"
                                class="meta absolute top-0 left-0 p-2 w-full text-right">
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

    <aside id="sidebar"
        x-data="{translate: '80px', expand_collections: false}"
        @mouseover="translate = '100%'" @mouseleave="translate = '80px'; expand_collections = false;"
        class="flex fixed top-0 right-0 transform transition min-h-screen shadow-2xl z-50"
        :style="`transform: translateX(calc(100% - ${translate}))`">

        <livewire:collections-aside />
        
        <div id="selection"
            class="min-h-screen max-h-screen w-80 bg-black p-4 overflow-y-auto overflow-x-hidden"
            x-data="{resolution: 280}">
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
                <div>
                    @foreach ($selection as $image)
                        <div class="mt-4 cursor-not-allowed" wire:key="selected_{{ $image['id'] }}"
                            wire:click.defer="forget({{ $image['id'] }})">
                            <img class="w-full"
                                style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges; min-height: 200px;"
                                src="https://pia-iiif.dhlab.unibas.ch/{{ $image['base_path'] }}/{{ $image['signature'] }}.jp2/full/280,/0/default.jpg"
                                alt="{{ $image['title'] }}" title="{{ $image['title'] }}">
                        </div>
                    @endforeach
                </div>
                @if(count($selection))
                <div class="my-4">
                    <button wire:click="delete_selection()" type="button"
                    class="py-2 px-4 rounded-full border border-white text-white hover:bg-red-500 hover:text-white text-sm">Delete Selection</button>
                </div>
                @endif
            </div>
        </div>
    </aside>

    <script>

        const config = {
            rootMargin: '0px 0px 0px 0px',
            threshold: 0
        };
    
        let observer = new IntersectionObserver(function(entries, self) {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    entry.target.src = entry.target.dataset.src;
                    self.unobserve(entry.target);
                }
            });
        }, config);

    </script>
    
</div>