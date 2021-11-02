@extends('base')

@section('content')
<div x-data="app" class="p-4">

    <div class="fixed top-0 left-0 h-full w-full bg-blue-500 bg-opacity-75 flex justify-around items-center z-50" x-show="loading">
        <span class="font-bold text-white text-8xl">Loading…</span>
    </div>
    
    <header class="mb-6 flex justify-between">
        <form action="{{ env('API_URL') }}" class="flex">
            <input type="text" name="query"
                class="border py-1 px-4 mr-2"
                x-model="query">
            <button type="submit"
                class="border py-1 px-4 text-sm bg-black text-white"
                @click.prevent="fetch_images">Search</button>
        </form>
        <div>
            <input 
                class="h-full"
                type="range" min="1" max="12" value="9"
                x-model="columns">
        </div>
        <p x-text="`${images().length} Resultate`" class="text-gray-500"></p>
    </header>

    <main>
        <div id="keywords" class="mb-6" x-data="{show_keywords: false}">
            <span @click="show_keywords = ! show_keywords"
                class="inline-block py-1 px-3 text-xs mr-2 mb-2 rounded-full cursor-pointer"
                :class="!show_keywords ? 'bg-black text-white' : ''">#</span>
            <template x-for="keyword in keywords" :key="keyword.id">
                <span
                    class="inline-block py-1 px-3 bg-black text-white text-xs mr-2 mb-2 rounded-full" 
                    x-text="keyword.attributes.label" x-show="show_keywords"></span>
            </template>
        </div>

        <div id="images" class="pb-20">
            <div class="grid gap-4 grid-flow-row" :class="`grid-cols-${13-columns}`">
                <template x-for="image in images" :key="image.id">
                    <div
                        x-data="{resolution: 50, loaded: false, show_meta: false}"
                        x-show="loaded" x-intersect.once="resolution = 320"
                        @mouseover="show_meta = true" @mouseout="show_meta = false">
                        <div class="relative">
                            <img class="w-full"
                            :src="`${image.links.related}full/${resolution},/0/default.jpg`"
                            :alt="image.attributes.title"
                            @load="loaded = true"
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
                            }"
                            style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
                            <div class="meta absolute bottom-0 left-0 p-2 w-full text-xs underline bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500"
                                x-show="show_meta">
                                <div class="links">
                                    <a target="_blank" :href="image.links.related" class="mr-2">IIIF</a>
                                    <a target="_blank" :href="image.links.self" class="mr-2">JSON</a>
                                    <a target="_blank" :href="`https://data.dasch.swiss/resources/${image.attributes.salsah_id}`">SALSAH</a>
                                </div>
                                <div class="keywords">
                                    <template x-for="keyword in image.relationships.keywords.data" :key="keyword.id">
                                        <span
                                            class="inline-block py-1 px-3 bg-black text-white text-xs mr-2 mt-2 rounded-full" 
                                            x-text="get_keyword_by_id(keyword.id)"></span>
                                    </template>
                                </div>
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
                        @click="() => {
                            selection = selection.filter(item => item !== image)
                        }">
                        <img
                            :src="`${image.links.related}full/${resolution},/0/default.jpg`"
                            :alt="image.attributes.title"
                            style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
                    </div>
                </template>
            </div>
            <div id="collection" class="flex pb-4 px-4" x-show="selection.length">
                <form action="{{ route('collections.store') }}" method="post" display="flex">
                    @csrf
                    <input type="hidden" name="image_ids" :value="selection.map(s => {return s.id}).join(',')">
                    <input type="hidden" name="collection_id" x-model="collection.id">
                    <input type="text" name="label" class="p-2 px-4 mr-2 bg-white text-black" placeholder="Label" x-model="collection.label" required>
                    <button type="submit" class="p-2 px-4 mr-2 border border-white text-white hover:bg-white hover:text-black">Save Collection</button>
                </form>
            </div>
        </div>
    </main>

    <script>

        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                api_url: '{{ env('API_URL') }}',

                query: '',
                query_min_chars: 3,

                data: {
                    data: [],
                    included: []
                },
                data_images: [],
                data_keywords: [],

                images() {
                    return this.data.data;
                },
                selection: [],
                collection: {
                    id: '',
                    label: ''
                },

                keywords() {
                    return this.data.included.filter(include => {
                        return include.type == 'keywords';
                    });
                },
                get_keyword_by_id(id) {
                    let keyword = this.keywords().filter(keyword => {
                        return keyword.id == id;
                    });
                    return keyword.length ? keyword[0].attributes.label : '' ;
                },

                page: 1,
                page_size: 5,
                max_results: 250,

                loading: false,
                columns: 9,

                init() {
                    let params = new URLSearchParams(window.location.search),
                        url = new URL(window.location.href);
    
                    if(params.has('query')) {
                        this.query = params.get('query')
                        this.fetch_images()
                    }

                    if(params.has('collection')) {
                        this.collection.id = params.get('collection')
                        this.fetch_collection()
                    }

                    this.$watch('query', (value) => {
                        if(value.length) {
                            url.searchParams.set('query', value)
                        } else {
                            url.searchParams.delete('query')
                        }
                        history.pushState(null, document.title, url.toString())
                    });
                },

                fetch_images() {
                    if(this.query.length >= this.query_min_chars) {
                        
                        this.loading = true;

                        fetch(`${this.api_url}images?filter[title]=${this.query}&include=keywords`)
                            .then(response => response.json())
                            .then(response => {
                                console.log(response)
                                if(response.data.length > this.max_results) {
                                    if(confirm(`${response.data.length} Resultate gefunden. Es kann zu einer längeren Ladezeit kommen.`)) {
                                        this.data = response;
                                        this.loading = false;
                                    }
                                } else {
                                    this.data = response;
                                    this.loading = false;
                                }
                            });
                    }
                },

                fetch_collection() {
                    this.loading = true;
                    fetch(`${this.api_url}collections/${this.collection.id}?include=images`)
                        .then(response => response.json())
                        .then(response => {
                            console.log(response)
                            this.collection.label = response.data.attributes.label;
                            this.selection = response.included;
                            this.loading = false;
                        });
                },
            }))
        })

    </script>

</div>
@endsection
