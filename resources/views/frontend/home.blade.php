@extends('base')

@section('content')

<div id="app" class="p-4" v-scope @mounted="init">

    <header class="mb-6 flex justify-between">
        <form action="{{ env('API_URL') }}" class="flex">
            <input type="text" name="query"
                class="border py-1 px-4 mr-2"
                v-model="query">
            <button type="submit"
                class="border py-1 px-4 text-sm bg-black text-white"
                @click.prevent="fetch_images">Search</button>
        </form>
        <div>
            <input 
                class="h-full"
                type="range" min="1" max="12" value="9"
                v-model="columns">
        </div>
        <div class="flex flex-wrap items-center">
            <span v-effect="$el.textContent = `${images.length}/${total} Resultate`" class="inline-block py-1 px-3 text-gray-500 text-xs"></span>
            <a href="{{ route('collections.index') }}"
                class="inline-block py-1 px-3 text-xs rounded-full cursor-pointer bg-black text-white ml-4">Collections</a>
        </div>
        
    </header>

    <main>
        <div id="keywords" class="mb-6" v-scope="{show_keywords: false}">
            <span @click="show_keywords = ! show_keywords"
                class="inline-block py-1 px-3 text-xs mr-2 mb-2 rounded-full cursor-pointer"
                :class="!show_keywords ? 'bg-black text-white' : ''"
                v-effect="$el.textContent = `# ${keywords.length}`"></span>
            <template v-for="keyword in keywords">
                <span
                    class="inline-block py-1 px-3 bg-black text-white text-xs mr-2 mb-2 rounded-full" 
                    v-show="show_keywords">@{{ keyword.attributes.label }}</span>
            </template>
        </div>

        <div id="images" class="pb-20">
            <div class="grid gap-4 grid-flow-row" :class="`grid-cols-${13-columns}`">
                <template v-for="image in images" :key="image.id">
                    <div v-scope="{loaded: false, show_meta: false}">
                        <div class="relative"
                            v-show="loaded" :style="loaded ? '' : 'height: 300px;'"
                            @mouseover="show_meta = true" @mouseout="show_meta = false">
                            
                            <img class="w-full"
                                style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;"

                                :src="`${image.links.related}full/50,/0/default.jpg`"
                                :data-src="`${image.links.related}full/320,/0/default.jpg`"
                                :alt="image.attributes.title"

                                @load="loaded = true; observer.observe($el);"
                                @click="
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
                                ">
                            <div class="meta absolute bottom-0 left-0 p-2 w-full text-xs underline bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500"
                                v-show="show_meta">
                                <div class="links">
                                    <a target="_blank" :href="image.links.related" class="mr-2">IIIF</a>
                                    <a target="_blank" :href="image.links.self" class="mr-2">JSON</a>
                                    <a target="_blank" :href="`https://data.dasch.swiss/resources/${image.attributes.salsah_id}`">SALSAH</a>
                                </div>
                                <div class="keywords">
                                    {{-- 
                                    <template x-for="keyword in image.relationships.keywords.data" :key="keyword.id">
                                        <span
                                            class="inline-block py-1 px-3 bg-black text-white text-xs mr-2 mt-2 rounded-full" 
                                            x-text="get_keyword_by_id(keyword.id)"></span>
                                    </template>
                                    --}}
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
            v-scope="{resolution: 200, translate: 75}"
            @mouseover="translate = 0" @mouseout="translate = 75">
            <div class="overflow-x-scroll whitespace-nowrap pr-4">
                <template v-for="image in selection" :key="image.id">
                    <div class="inline-block my-4 ml-4"
                        @click="selection = selection.filter(item => item !== image)">
                        <img
                            :src="`${image.links.related}full/${resolution},/0/default.jpg`"
                            :alt="image.attributes.title"
                            style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
                    </div>
                </template>
            </div>
            <div id="collection" class="flex pb-4 px-4" v-show="selection.length">
                <form action="{{ route('collections.store') }}" method="post" display="flex">
                    @csrf
                    <input type="hidden" name="image_ids" :value="selection.map(s => {return s.id}).join(',')">
                    <input type="hidden" name="collection_id" v-model="collection.id">
                    <input type="text" name="label" class="p-2 px-4 mr-2 bg-white text-black" placeholder="Label" v-model="collection.label" required>
                    <button type="submit" class="p-2 px-4 mr-2 border border-white text-white hover:bg-white hover:text-black">Save Collection</button>
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

    document.addEventListener('DOMContentLoaded', evt => {
        PetiteVue.createApp({
            
            api_url: '{{ env('API_URL') }}',

            query_string: '',
            query_min_chars: 3,

            data: {
                data: [],
                included: []
            },

            images: [],
            keywords: [],
            selection: [],
            collection: {
                id: '',
                label: ''
            },

            page: 1,
            page_size: 50,
            total: 0,

            loading: false,
            columns: 9,
            
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
                    this.collection.id = params.get('collection')
                    this.fetch_collection()
                }
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
                fetch(`${this.api_url}images?filter[title]=${this.query}&include=keywords&page[number]=${this.page}&page[size]=${this.page_size}`)
                    .then(response => response.json())
                    .then(response => {
                        this.fetched(response);
                    });
            },

            fetched(response) {
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

            get_keyword_by_id(id) {
                let keyword = this.keywords().filter(keyword => {
                    return keyword.id == id;
                });
                return keyword.length ? keyword[0].attributes.label : '' ;
            },
        }).mount();
    });

</script>
@endsection
