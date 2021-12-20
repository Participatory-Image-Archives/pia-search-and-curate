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
                class="py-1 px-4 mr-10 text-sm bg-black text-white"
                @click.prevent="fetch_images">Search</button>

            <x-buttons.ghost label="⚙️" @click="show_settings = ! show_settings"/>
        </form>
        <div>
            <span x-text="`${images.length} of ${total} Results loaded`" class="inline-block py-1 px-3 text-gray-500 text-xs"></span>

            <x-buttons.bare label="Delete Selection" @click="delete_selection" x-show="selection.length"/>
            @include('partials.lists-dropdown')
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
            <label class="block">
                <input type="radio" name="search_focus_choices" value="dates"
                    x-model="search_focus">
                Dates
            </label>
        </div>
        <div class="p-4" x-show="search_focus == 'dates'">
            <h3 class="text-xs">By Date</h3>
            <div>
                <label for="from" class="inline-block">On/From</label>
                <input type="date" name="from" class="border-b" x-model="date_from">
            </div>
            <div class="mb-2">
                <label for="to" class="inline-block">To</label>
                <input type="date" name="to" class="border-b" x-model="date_to">
            </div>
            <x-buttons.default label="Search" @click="fetch_images"/>
        </div>
    </div>

    <main>
        <div id="keywords" class="mb-6" x-data="{show_keywords: false}" x-show="keywords.length">
            <x-buttons.default x-text="`# ${keywords.length}`" @click="show_keywords = ! show_keywords"/>

            <template x-for="keyword in keywords">
                <x-links.default class="mb-2 mr-1"
                    x-bind:href="`/?keyword=${keyword.id}`"
                    x-show="show_keywords" x-text="keyword.attributes.label"/>
            </template>
        </div>

        @if($tagcloud)
            <div id="html-tagcloud" class="mx-auto w-full" style="height: 800px"></div>
        @endif

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
                                        target="_blank"
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
                        x-show="collection.id != ''"
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

            date_from: '',
            date_to: '',

            data: {
                data: [],
                included: []
            },

            images: [],
            keywords: [],
            consolidated_keywords: [],
            collection: {
                model: 'collections',
                id: '',
                label: ''
            },
            keyword: {
                model: 'keywords',
                id: '',
                label: ''
            },
            person: {
                model: 'people',
                id: '',
                name: ''
            },
            location: {
                model: 'locations',
                id: '',
                name: ''
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

            set dates(value) {
                let dates = value.split(',');
                this.date_from = dates[0];
                this.date_to = dates[1];

                let url = new URL(window.location.href);

                if(value.length) {
                    url.searchParams.set('dates', value)
                } else {
                    url.searchParams.delete('dates')
                }
                history.pushState(null, document.title, url.toString())
            },
            get dates() {
                return this.date_from+','+this.date_to;
            },

            // methods
            init() {
                let params = new URLSearchParams(window.location.search);

                if(params.has('query')) {
                    this.query = params.get('query')
                    this.fetch_images()
                }

                if(params.has('dates')) {
                    this.dates = params.get('dates')
                    this.search_focus = 'dates'
                    this.fetch_images()
                }

                if(params.has('collection')) {
                    localStorage.removeItem('selection');
                    this.collection.id = params.get('collection')
                    this.fetch_by_relation(this.collection)
                }

                if(params.has('keyword')) {
                    this.keyword.id = params.get('keyword')
                    this.fetch_by_relation(this.keyword)
                }

                if(params.has('person')) {
                    this.person.id = params.get('person')
                    this.fetch_by_relation(this.person)
                }

                if(params.has('location')) {
                    this.location.id = params.get('location')
                    this.fetch_by_relation(this.location)
                }

                if(localStorage.selection) {
                    this.selection = JSON.parse(localStorage.selection);
                }

                this.$watch('selection', value => {
                    localStorage.selection = JSON.stringify(value);
                })
            },

            fetch_images() {
                if(this.query.length >= this.query_min_chars || this.dates.length == 11 || this.dates.length == 21) {
                    
                    this.loading = true;
                    this.page = 1;
                    this.images = [];

                    this.fetch();
                } else {
                    alert('Nah…')
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
                if(this.search_focus == 'dates') {
                    this.dates = this.dates;
                    query = `${this.api_url}dates?filter[dates]=${this.dates}&include=images&page[number]=${this.page}&page[size]=${this.page_size}`
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
                        
                        @if($tagcloud)

                        let counted_keywords = [], consolidated_keywords = [];

                        keywords.forEach(el => {
                            if(!counted_keywords[el.id]) {
                                counted_keywords[el.id] = {
                                    'id': el.id,
                                    'label': el.attributes.label,
                                    'count': 1
                                }
                            } else {
                                counted_keywords[el.id].count++;
                            }
                        });

                        counted_keywords.forEach(el => {
                            consolidated_keywords.push([
                                el.label,
                                el.count,
                                el.id
                            ])
                        });

                        WordCloud(document.getElementById('html-tagcloud'), {
                            list: consolidated_keywords,
                            gridSize: Math.round(16 * document.querySelector('#html-tagcloud').offsetWidth / 1024),
                            weightFactor: function (size) {
                                return Math.pow(size+1.5, 3) * document.querySelector('#html-tagcloud').offsetWidth / 1024;
                            },
                            minSize: '40px',
                            rotateRatio: 0.5,
                            rotationSteps: 2,
                            classes: 'cursor-pointer',
                            click: function(item) {
                                window.location = '/?keyword='+item[2];
                            }
                        } );

                        @endif

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
                if(this.search_focus == 'dates') {
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

            fetch_by_relation(relation) {
                this.loading = true;
                fetch(`${this.api_url}${relation.model}/${relation.id}?include=images`)
                    .then(response => response.json())
                    .then(response => {
                        if(response.included) {
                            relation.label = response.data.attributes.label;
                            if(relation.model == 'collections') {
                                this.selection = response.included;
                            } else {
                                this.images = response.included;
                            }
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

@if($tagcloud)
<script src="{{ asset('js/wordcloud2.js') }}"></script>
@endif

@endsection
