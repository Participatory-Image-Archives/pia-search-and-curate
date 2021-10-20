@extends('base')

@section('content')
<div x-data="app" class="p-4">
    <div class="fixed top-0 left-0 h-full w-full bg-green-500 bg-opacity-50 flex justify-around items-center z-50" x-show="loading">
        <span class="font-bold text-white text-8xl">Loading…</span>
    </div>
    <div class="shadow-xl py-6 px-8 mb-4 fixed top-0 left-0 z-10 bg-white w-full"
        :style="{ opacity: show_selection ? '0.25' : '1.0' }"
        @mouseover="show_controls = true" @mouseleave="show_controls = false">
        <div class="flex">
            <form action="/api/images" id="fuzzy_search" class="flex flex-1">
                <div class="" style="margin-bottom: 0;">
                    <input type="text" name="q" class="p-2 px-3 mr-2 border border-black-500  focus:outline-none"
                        x-model="q">
                    <button class="p-2 px-4 mr-2 border border-black-500 " @click.prevent="fetch_images">Suchen</button>
                </div>
            </form>
            <div class="flex flex-1 justify-end">
                <p class="p-2">
                    <span class="results_count is-bold" x-text="filtered_images().length"></span> Bild(er) 
                </p>
                <p>
                    <button class="p-2 px-4 mr-2 border border-black-500 " type="button"
                        @click="show_all">anzeigen.</button>
                </p>
            </div>
        </div>
        <div class="mt-4" x-show="show_controls" x-transition>
            <div class="flex">
                <input type="range" id="grid-col-size" name="grid-col-size" min="1" max="12" value="4" class="w-1/4" x-model="grid_col_size">
                <label for="show-details" class="w-1/4 ml-4">
                    <input type="checkbox" id="show-details" name="show-details" min="1" max="12" value="4" x-model="show_details"> Show Details
                </label>
            </div>
            <div id="tags_wrapper" class="mt-4">
                <template x-for="keyword in keywords" :key="keyword.id">
                    <span class="text-xs px-4 py-1 border border-black mr-1 mb-1 inline-block cursor-pointer" :class="keyword.active ? 'bg-black text-white' : 'bg-white text-black'"
                        x-text="keyword.label" @click="keyword.active = ! keyword.active"></span>
                </template>
                <span class="text-xs px-2 py-1 mr-1 mb-1 inline-block cursor-pointer border bg-blue-500 text-white border-blue-500 hover:bg-white hover:text-blue-500"
                    @click="keywords.forEach(el => el.active = true)">all</span>
                <span class="text-xs px-2 py-1 mr-1 mb-1 inline-block cursor-pointer border bg-blue-500 text-white border-blue-500 hover:bg-white hover:text-blue-500"
                    @click="keywords.forEach(el => el.active = false)">none</span>
            </div>
        </div>
    </div>



    <div class="grid grid-flow-row gap-4" :class="'grid-cols-'+(13-grid_col_size)"
        style="margin-top: 100px;"
        :style="{ opacity: show_selection ? '0.25' : '1.0' }">
        <template x-for="image in filtered_images" :key="image.id">
            <div
                x-data="{ img: image }"
                x-init="() => {
                    for(let i = 0; i <= ids.length; i++) {
                        if(img.id == ids[i]) {
                            img.selected = true
                            img.visible = true
                        }
                    }
                }"
                @click="() => {
                    let contains = false;
                    selection.forEach(el => {
                        if(el.id == img.id) {
                            contains = true;
                        }
                    })
                    if(! contains) {
                        selection.push(img)
                        img.selected = true
                    }
                }">
                <div class="relative overflow-hidden border-2 border-gray-100" style="cursor: copy;" :class="img.selected && 'border-blue-600'">
                    <img class="w-full result" :class="img.visible ? '' : 'p-2 px-4 text-xs'"
                        :alt="img.title" :name="img.title" :src="img.visible && img.src" :data-src="img.src">
                    <div x-show="show_details" class="absolute left-0 bottom-0">
                        <div class="flex p-2 px-4">
                            <button class="cursor-pointer mr-2" :class="img.visible && 'hidden'"
                                @click.stop="img.visible = true">🖼️</button>
                            <a class="" target="_blank"
                                :href="'https://data.dasch.swiss/resources/'+img.salsah_id"
                                @click.stop>🔗</a>
                        </div>
                        <div class="p-2 px-4">
                            <template x-for="keyword in img.keywords" :key="keyword.id + '' + Math.random()">
                                <span class="text-xs p-1 py-0 mr-1 mb-1 inline-block bg-gray-500 text-white"
                                    x-text="keyword.label"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <button
        @mouseover="show_selection = true" 
        type="button" class="fixed bg-black text-white"
        style="bottom: 10px; right: 10px; width: 50px; line-height: 50px; border-radius: 50%;">&#9745;</button>

    <div class="fixed left-0 h-full w-full bg-black p-8 shadow-2xl overflow-scroll z-20" style="top: 100%; box-shadow: 0 -10px 20px rgba(0, 0, 0, 0.25)"
        :style="{ top: show_selection ? '0' : '100%', transition: '.25s' }"
        @mouseover="show_selection = true" @mouseleave="show_selection = false">
        <div class="grid grid-flow-row gap-4 mb-8" :class="'grid-cols-'+(7-Math.ceil(grid_col_size/2))">
            
            <template x-for="image in selection" :key="image.id">
                <div>
                    <div x-data="{ img: image }"
                        @click="() => {
                            images.forEach(el => {
                                if(el.id == img.id) {
                                    el.selected = false
                                }
                            })
                            selection = selection.filter(item => item !== img)
                        }" style="cursor: not-allowed;">
                        <img class="w-full" :src="img.src"
                            :alt="img.title" :name="img.title">
                    </div>
                </div>
            </template>
        </div>
        <form action="{{ route('collections.store') }}" method="post" display="flex">
            @csrf
            <input type="hidden" name="image_ids" x-ref="image_ids">
            <input type="hidden" name="collection_id" x-model="collection.id">
            <input type="text" name="label" class="p-2 px-4 mr-2 border border-white" placeholder="Label" x-model="collection.label" required>
            <button type="submit" class="p-2 px-4 mr-2 border border-white text-white hover:bg-white hover:text-black">Save Collection</button>
        </form>
    </div>

    <script>

        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                collection: {
                    id: '',
                    label: ''
                },
                images: [],
                selection: [],
                keywords: [],

                q: '',
                loading: false,

                grid_col_size: 6,
                show_details: true,
                show_selection: false,
                show_controls: false,

                fetch_images() {
                    if(this.q.length >= 3) {
                        this.loading = true;
                        fetch('api/images?q='+this.q)
                            .then(response => response.json())
                            .then(data => {

                                let _keywords = [], _l = data.length;

                                data.map(el => {
                                    el.visible = false;
                                    el.selected = false;
                                    el.src = image_call(el.collections, el.signature, el.salsah_id);

                                    el.keyword_ids = [];

                                    el.keywords.forEach(keyword => {
                                        keyword.active = true;
                                        _keywords.push(keyword);
                                        el.keyword_ids.push(keyword.id);
                                    });
                                })

                                _keywords = _keywords.filter((thing, index, self) =>
                                    index === self.findIndex((t) => (
                                        t.id === thing.id && t.label === thing.label
                                    ))
                                )

                                _keywords.sort((a, b) => {
                                    if ( a.label < b.label ){
                                        return -1;
                                    }
                                    if ( a.label > b.label ){
                                        return 1;
                                    }
                                    return 0;
                                })

                                this.keywords = _keywords;
                                this.images = data;

                                if(this.images.length < 200) {
                                    this.filtered_images().forEach(el => {
                                    el.visible = true;
                                })
                                }

                                this.loading = false;
                            })
                    }
                },

                fetch_collection() {
                    this.loading = true;
                    fetch('api/collection?c='+this.collection.id)
                        .then(response => response.json())
                        .then(data => {
                            this.ids = data.image_ids.split(',');
                            this.collection.label = data.label;
                            this.loading = false;
                        });
                },

                get ids() {
                    let _ids = [];
                    this.selection.forEach(el => {
                        _ids.push(el.id)
                    })
                    return _ids;
                },

                set ids(value) {
                    fetch('api/ids?ids='+value)
                        .then(response => response.json())
                        .then(data => {
                                data.forEach((el, i) => {
                                    el.visible = true;
                                    el.selected = true;
                                    el.src = image_call(el.collections, el.signature, el.salsah_id);
                                })
                                this.selection = data
                            })
                },

                show_all() {
                    this.filtered_images().forEach(el => {
                        el.visible = true;
                    })
                },

                filtered_images() {
                    let images = (this.keywords.length > this.active_keywords_ids().length)
                        ? this.images.filter(image => {
                            let keywords = image.keywords.map(keyword => keyword.id);
                            return this.active_keywords_ids().some(r => keywords.includes(r));
                        })
                        : this.images;
                        
                    //let images = this.images;

                    return images;
                },

                active_keywords_ids() {
                    return this.keywords.filter(el => el.active).map(el => el.id);
                },

                init() {
                    let params = new URLSearchParams(window.location.search)
    
                    if(params.has('q')) {
                        this.q = params.get('q')
                        this.fetch_images()
                    }

                    if(params.has('ids')) {
                        this.ids = params.get('ids');
                    }

                    if(params.has('c')) {
                        this.collection.id = params.get('c')
                        this.fetch_collection()
                    }

                    const url = new URL(window.location.href)

                    this.$watch('q', (value) => {
                        if(value.length) {
                            url.searchParams.set('q', value)
                        } else {
                            url.searchParams.delete('q')
                        }
                        history.pushState(null, document.title, url.toString())
                    });

                    this.$watch('selection', (value) => {
                        if(this.ids.length) {
                            url.searchParams.set('ids', this.ids.join(','));
                            this.$refs.image_ids.value = this.ids.join(',');
                        } else {
                            url.searchParams.delete('ids')
                        }
                        
                        history.pushState(null, document.title, url.toString())
                    });
                }
            }))
        })

        function image_call(collections, signature, salsah_id) {
            if(collections && collections.length){
                for(let c in collections){
                    if(collections[c].origin == 'salsah') {
                        return `https://pia-iiif.dhlab.unibas.ch/${collections[c].signature}/${signature}.jp2/full/640,/0/default.jpg`
                    }
                }
            } else {
                return '';
            }
        }

    </script>

</div>
@endsection
