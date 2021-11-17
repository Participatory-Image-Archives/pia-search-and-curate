@extends('base')

@section('content')

<div id="app" class="p-4" x-data="app">

    <div class="fixed top-0 left-0 h-full w-full bg-blue-500 bg-opacity-75 flex justify-around items-center z-50" x-show="loading">
        <span class="font-bold text-white text-8xl">Loading…</span>
    </div>

    <header class="mb-6 flex justify-between">
        <form action="{{ env('API_URL') }}" class="flex">
            <input type="text" name="query"
                class="border py-1 px-4 mr-2"
                x-model="query">
            <button type="submit"
                class="py-1 px-4 text-sm bg-black text-white"
                @click.prevent="fetch_images">Search</button>
            <button type="button"
                class="py-1 px-4 text-sm"
                @click="show_settings = ! show_settings">
                ⚙️
            </button>
        </form>
        <div class="flex flex-wrap items-center">
            <span x-text="`${images.length} of ${total} Results loaded`" class="inline-block py-1 px-3 text-gray-500 text-xs"></span>
            <button type="button"
                class="inline-block py-1 text-xs rounded-full cursor-pointer ml-4 text-red-500 underline"
                @click="delete_selection" x-show="selection.length">Delete Selection</button>
            <a href="{{ route('collections.index') }}"
                class="inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white ml-4">Collections</a>
            <a href="{{ route('keywords.index') }}"
                class="inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white ml-4">Keywords</a>
        </div>
    </header>

    <div class="shadow-2xl flex mb-4" x-show="show_settings">
        <div class="p-4">
            <h3 class="text-xs">Column Count</h3>
            <input 
                class="h-full"
                type="range" min="1" max="12" value="9"
                x-model="columns">
        </div>
        <div class="p-4">
            <h3 class="text-xs">Search focus</h3>
            <label class="block">
                <input type="radio" name="search_focus_choices" value="fuzzy"
                    x-model="search_focus">
                Images: Title, Old Number, Signature
            </label>
            <label class="block">
                <input type="radio" name="search_focus_choices" value="comments"
                    x-model="search_focus">
                Comments
            </label>
        </div>
    </div>

    <main>
        <div id="keywords" class="mb-6" x-data="{show_keywords: false}">
            <span @click="show_keywords = ! show_keywords"
                class="inline-block py-1 px-3 text-xs mr-2 mb-2 rounded-full cursor-pointer"
                :class="!show_keywords ? 'bg-black text-white' : ''"
                x-text="`# ${keywords.length}`"></span>
            <template x-for="keyword in keywords">
                <span
                    class="inline-block py-1 px-3 bg-black text-white text-xs mr-2 mb-2 rounded-full" 
                    x-show="show_keywords" x-text="keyword.attributes.label"></span>
            </template>
        </div>

        <div id="images" class="pb-20">
            <div class="grid gap-4 grid-flow-row" :class="`grid-cols-${13-columns}`">
                <template x-for="image in images" :key="image.id">
                    <div x-data="{loaded: false, show_meta: false}">
                        <div class="relative"
                            
                            x-show="loaded" :style="loaded ? '' : 'height: 300px;'"
                            @mouseover="show_meta = true" @mouseout="show_meta = false">
                            
                            <img class="w-full"
                                style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;"

                                :src="`${image.links.related}full/50,/0/default.jpg`"
                                :data-src="`${image.links.related}full/320,/0/default.jpg`"
                                :alt="image.attributes.title"

                                @load="loaded = true; observer.observe($el);">
                            <div class="meta absolute bottom-0 left-0 p-2 w-full text-right">
                                    <a
                                        :href="`/images/${image.id}`"
                                        class="rounded-full bg-blue-500 text-white text-xs py-1 px-3">Details</a>
                                    <button
                                        type="button"
                                        class="rounded-full bg-white hover:bg-black hover:text-white text-xs py-1 px-3"
                                        @click="() => {
                                        let contains = false;
                                        selection.forEach(el => {
                                            if(el.id == image.id) {
                                                contains = true;
                                            }
                                        })
                                        if(! contains) {
                                            selection.push(image)
                                            image.selected = true
                                        }
                                    }">+ Add</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div id="selection" x-ref="selection"
            class="fixed bottom-0 left-0 w-full bg-black transition"
            style="box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.5);"
            :style="`transform: translateY(${translate}%)`"
            x-data="{resolution: 200, translate: 75}"
            @mouseover="translate = 0" @mouseout="translate = 75">
            <div class="overflow-x-scroll whitespace-nowrap pr-4">
                <template x-for="image in selection" :key="image.id">
                    <div class="inline-block my-4 ml-4"
                        @click="selection = selection.filter(item => item !== image)">
                        <img
                            :src="`${image.links.related}full/${resolution},/0/default.jpg`"
                            :alt="image.attributes.title"
                            style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
                    </div>
                </template>
            </div>
            <div id="collection" class="flex pb-4 px-4" x-show="selection.length">
                <form action="{{ route('collections.store') }}" method="post" display="flex" x-ref="collection_form">
                    @csrf
                    <input type="hidden" name="image_ids" :value="selection.map(s => {return s.id}).join(',')">
                    <input type="hidden" name="collection_id" x-model="collection.id">
                    <input type="text" name="label" class="p-2 px-4 mr-2 bg-white text-black" placeholder="Label" x-model="collection.label" required>
                    <button type="submit" class="p-2 px-4 mr-2 border border-white text-white hover:bg-white hover:text-black">Save Collection</button>
                    <button type="button" class="p-2 px-4 mr-2 text-white text-xs underline"
                        @click.prevent="() => {
                            collection.id = '';
                            setTimeout(() => {
                                $refs.collection_form.submit();
                            }, 100);
                        }">Add as new Collection</button>
                </form>
            </div>
        </div>
    </main>

</div>

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

    document.addEventListener('alpine:init', () => {
        Alpine.data('app', () => ({
            
            api_url: '{{ env('API_URL') }}',

            query_string: '',
            query_min_chars: 3,

            data: {
                data: [],
                included: []
            },

            images: [],
            keywords: [],
            keyword: {
                id: '',
                label: ''
            },
            collection: {
                id: '',
                label: ''
            },
            selection: [],

            page: 1,
            page_size: 50,
            total: 0,

            loading: false,
            columns: 9,
            search_focus: 'fuzzy',
            show_settings: false,
            
            // setters, getters
            set query(value) {
                this.query_string = value;

                let url = new URL(window.location.href);

                if(value.length) {
                    url.searchParams.set('query', value)
                } else {
                    url.searchParams.delete('query')
                }
                history.pushState(null, document.title, url.toString())
            },
            get query() {
                return this.query_string;
            },

            // methods
            init() {
                let params = new URLSearchParams(window.location.search);

                if(params.has('query')) {
                    this.query = params.get('query')
                    this.fetch_images()
                }

                if(params.has('collection')) {
                    localStorage.removeItem('selection');
                    this.collection.id = params.get('collection')
                    this.fetch_collection()
                }

                if(params.has('keyword')) {
                    this.keyword.id = params.get('keyword')
                    this.fetch_keyword()
                }

                if(localStorage.selection) {
                    this.selection = JSON.parse(localStorage.selection);
                }

                this.$watch('selection', value => {
                    localStorage.selection = JSON.stringify(value);
                })
            },

            fetch_images() {
                if(this.query.length >= this.query_min_chars) {
                    
                    this.loading = true;
                    this.page = 1;
                    this.images = [];

                    this.fetch();
                }
            },

            fetch() {
                let query = '';
                if(this.search_focus == 'fuzzy') {
                    query = `${this.api_url}images?filter[omni]=${this.query}&include=keywords&page[number]=${this.page}&page[size]=${this.page_size}`
                }
                if(this.search_focus == 'comments') {
                    query = `${this.api_url}comments?filter[comment]=${this.query}&include=images&page[number]=${this.page}&page[size]=${this.page_size}`
                }
                fetch(query)
                    .then(response => response.json())
                    .then(response => {
                        this.fetched(response);
                    });
            },

            fetched(response) {
                if(this.search_focus == 'fuzzy') {
                    this.images = this.images.concat(response.data);

                    if(response.included) {
                        let keywords = this.keywords.concat(
                            response.included.filter(include => {
                                return include.type == 'keywords';
                            })
                        );

                        this.keywords = keywords.filter((keyword, index, self) =>
                            index === self.findIndex((kw) => (
                                kw.id === keyword.id
                            ))
                        );
                    }
                }
                if(this.search_focus == 'comments') {
                    if(response.included) {
                        this.images = this.images.concat(response.included);
                    }
                }

                this.loading = false;

                if(response.links.next) {
                    this.page++;
                    this.fetch();
                    this.total = response.meta.page.total;
                } else {
                    this.total = this.images.length;
                }
            },

            fetch_collection() {
                this.loading = true;
                fetch(`${this.api_url}collections/${this.collection.id}?include=images`)
                    .then(response => response.json())
                    .then(response => {
                        this.collection.label = response.data.attributes.label;
                        this.selection = response.included;
                        this.loading = false;
                    });
            },

            fetch_keyword() {
                this.loading = true;
                console.log(`${this.api_url}keywords/${this.keyword.id}?include=images`);
                fetch(`${this.api_url}keywords/${this.keyword.id}?include=images`)
                    .then(response => response.json())
                    .then(response => {
                        if(response.included) {
                            this.images = response.included;
                            this.total = this.images.length;
                        }
                        this.loading = false;
                    });
            },

            get_keyword_by_id(id) {
                let keyword = this.keywords().filter(keyword => {
                    return keyword.id == id;
                });
                return keyword.length ? keyword[0].attributes.label : '' ;
            },

            delete_selection() {
                this.selection = [];
                this.collection.id = '';
                this.collection.label = '';

                let url = new URL(window.location.href);
                url.searchParams.delete('collection')
                history.pushState(null, document.title, url.toString())
            }
        }));
    });

</script>
@endsection
