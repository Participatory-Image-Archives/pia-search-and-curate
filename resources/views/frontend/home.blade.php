@extends('base')

@section('content')

<div id="app" class="p-4 bg-gray-100 min-h-screen" x-data="app">

    <div class="fixed top-0 left-0 h-full w-full bg-gray-100 bg-opacity-75 justify-around items-center z-50 hidden">
        <span class="font-bold text-white text-8xl">Loading…</span>
    </div>

    <livewire:search />

</div>

    {{--<div class="modal-wrap fixed top-0 left-0 w-screen h-screen flex items-center justify-center bg-gray-500 bg-opacity-75 z-50"
        x-show="modal_map || modal_dates">
        <div class="modal-map inline-block bg-white p-4 rounded-xl" x-show="modal_map">
            <iframe src="{{ route('search.byCoordinates') }}" frameborder="0" width="400px" height="450px"></iframe>
        </div>
        <div class="modal-map inline-block bg-white p-4 rounded-xl" x-show="modal_dates">
            <iframe src="{{ route('search.byDates') }}" frameborder="0" width="400px" height="150px"></iframe>
        </div>
    </div>

    <main class="pr-20">
        <div id="keywords" x-data="{show_keywords: false}" x-show="keywords.length">
            <x-buttons.default x-text="`# ${keywords.length}`" @click="show_keywords = ! show_keywords"/>

            <template x-for="keyword in keywords">
                <x-links.default class="mb-2 mr-1"
                    x-bind:href="`/?keyword=${keyword.id}`"
                    x-show="show_keywords" x-text="keyword.attributes.label"/>
            </template>
        </div>

        @if($tagcloud)
            <div id="html-tagcloud" class="mx-auto w-full mb-8" style="height: 800px"></div>
        @endif

        <div x-show="show_images_otd" class="px-20 pb-20">
            <h2 class="text-center text-2xl mb-10">Random images of the day</h2>
            <div class="grid gap-10 grid-cols-3 grid-flow-row">
                @foreach ($images_otd as $image)
                <a href="{{ route('images.show', [$image]) }}" class="print-image" x-data="{show: false}" x-show="show">
                    <img class="inline-block mr-2 w-full"
                        src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/480,/0/default.jpg"
                        alt="{{$image->title}}" title="{{$image->title}}" @load="show = true">
                </a>
                @endforeach
            </div>
        </div>

        <div id="images" class="pb-20">
            
            <div class="text-right mb-2">
                <span x-text="`${images.length} of ${total} Results loaded`" class="inline-block text-gray-500 text-xs"></span>
            </div>

            <div class="grid gap-4 grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
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

        <div class="mb-28 text-center" x-show="images.length < total">
            <button @click="fetch_more" class="text-4xl rounded-full px-8 py-2 border border-black transition-all hover:bg-black hover:text-white">Load more results</button>
        </div>

    </main>

    <aside id="sidebar"
        x-data="{translate: '80px', expand_collections: false}"
        @mouseover="translate = '100%'" @mouseleave="translate = '80px'; expand_collections = false;"
        class="flex fixed top-0 right-0 transform transition min-h-screen shadow-2xl z-50"
        :style="`transform: translateX(calc(100% - ${translate}))`">
        
        @include('frontend.partials.aside-collections')

        <div id="selection" x-ref="selection"
            class="min-h-screen max-h-screen w-80 bg-black p-4 overflow-y-auto overflow-x-hidden"
            x-data="{resolution: 280}">
            <div>
                <div id="collection">
                    <form action="{{ route('collections.store') }}" method="post" x-ref="collection_form">
                        @csrf
                        <input type="hidden" name="image_ids" :value="selection.map(s => {return s.id}).join(',')">
                        <input type="hidden" name="collection_id" x-model="collection.id">
                        
                        <div class="flex">
                            <input type="text" name="label" class="py-2 px-6 w-56 focus:outline-none text-lg z-10 bg-white text-black rounded-full" placeholder="Label" x-model="collection.label" required>
                            <button type="submit" class="relative -left-5 text-lg z-0 pl-7 pr-3 border border-white text-white hover:bg-white hover:text-black">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            </button>
                        </div>
                        
                        <button type="button" class="p-2 px-4 text-white text-xs underline"
                            x-show="collection.id != ''"
                            @click.prevent="() => {
                                collection.id = '';
                                setTimeout(() => {
                                    $refs.collection_form.submit();
                                }, 100);
                            }">Add as new Collection</button>
                    </form>
                </div>
                <div>
                    <template x-for="image in selection" :key="image.id">
                        <div class="mt-4"
                            @click="if (confirm('Really remove?')) {
                                selection = selection.filter(item => item !== image)
                            }">
                            <img
                                class="w-full cursor-not-allowed"
                                :src="`${image.links.related}full/${resolution},/0/default.jpg`"
                                :alt="image.attributes.title"
                                style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
                        </div>
                    </template>
                </div>
                <div class="my-4">
                    <button @click="delete_selection" x-show="selection.length" class="py-2 px-4 rounded-full border border-white text-white hover:bg-red-500 hover:text-white text-sm">Delete Selection</button>
                </div>
            </div>
        </div>
    </aside>
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

            modal_map: false,
            modal_dates: false,
            show_images_otd: true,

            top_left_lat: '',
            top_left_lng: '',
            bottom_right_lat: '',
            bottom_right_lng: '',

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
                label: '',
                description: ''
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

            set coordinates(value) {
                let coordinates = value.split(',');
                
                this.top_left_lat = coordinates[0];
                this.top_left_lng = coordinates[1];
                this.bottom_right_lat = coordinates[2];
                this.bottom_right_lng = coordinates[3];

                let url = new URL(window.location.href);

                if(value.length) {
                    url.searchParams.set('coordinates', value)
                } else {
                    url.searchParams.delete('coordinates')
                }
                history.pushState(null, document.title, url.toString())
            },
            get coordinates() {
                return this.top_left_lat+','+this.top_left_lng+','+this.bottom_right_lat+','+this.bottom_right_lng
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

                if(params.has('coordinates')) {
                    this.coordinates = params.get('coordinates')
                    this.search_focus = 'coordinates'
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
                this.show_images_otd = false;

                if(this.query.length >= this.query_min_chars || this.dates.length == 11 || this.dates.length == 21 || this.coordinates.length > 0) {
                    
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
                if(this.search_focus == 'coordinates') {
                    this.coordinates = this.coordinates;
                    query = `${this.api_url}locations?filter[coordinates]=${this.coordinates}&include=images&page[number]=${this.page}&page[size]=${this.page_size}`
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
                if(this.search_focus == 'coordinates') {
                    if(response.included) {
                        this.images = this.images.concat(response.included);
                    }
                }

                this.loading = false;

                if(response.links.next) {
                    this.total = response.meta.page.total;
                } else {
                    this.total = this.images.length;
                }
            },

            fetch_more() {
                this.loading = true;
                this.page++;
                this.fetch();
            },

            fetch_by_relation(relation) {
                this.show_images_otd = false;
                this.loading = true;
                fetch(`${this.api_url}${relation.model}/${relation.id}?include=images`)
                    .then(response => response.json())
                    .then(response => {
                        if(response.included) {
                            relation.label = response.data.attributes.label;
                            if(relation.model == 'collections') {
                                this.selection = response.included;
                                relation.description = response.data.attributes.description;
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
                this.collection.description = '';

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

--}}

@endsection
