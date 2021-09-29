@extends('frontend/base')

@section('content')
<div x-data="app" class="p-4">
    <div class="fixed top-0 left-0 h-full w-full bg-green-500 bg-opacity-50 flex justify-around items-center" x-show="loading">
        <span class="font-bold text-white text-8xl">Loading…</span>
    </div>
    <div class="shadow-xl py-6 px-8 flex mb-4">
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

    <div id="tags_wrapper" class="shadow-xl py-4 px-8 mb-4">
        <template x-for="keyword in keywords" :key="keyword.id">
            <span class="text-xs px-4 py-1 border border-black mr-1 mb-1 inline-block cursor-pointer" :class="keyword.active ? 'bg-black text-white' : 'bg-white text-black'"
                x-text="keyword.label" @click="keyword.active = ! keyword.active"></span>
        </template>
        <span class="text-xs px-2 py-1 mr-1 mb-1 inline-block cursor-pointer border bg-blue-500 text-white border-blue-500 hover:bg-white hover:text-blue-500"
            @click="keywords.forEach(el => el.active = true)">all</span>
        <span class="text-xs px-2 py-1 mr-1 mb-1 inline-block cursor-pointer border bg-blue-500 text-white border-blue-500 hover:bg-white hover:text-blue-500"
            @click="keywords.forEach(el => el.active = false)">none</span>
    </div>

    <div class="shadow-xl py-4 px-8 mb-4 flex">
        <input type="range" id="grid-col-size" name="grid-col-size" min="1" max="12" value="4" class="w-3/4" x-model="grid_col_size">
        <label for="show-details" class="w-1/4 ml-4">
            <input type="checkbox" id="show-details" name="show-details" min="1" max="12" value="4" x-model="show_details"> Show Details
        </label>
    </div>

    <div class="grid grid-flow-row gap-4" :class="'grid-cols-'+grid_col_size">
        <template x-for="image in filtered_images" :key="image.id">
            <div class=" overflow-hidden border border-gray-100" :class="img.selected && 'border-blue-600'"
                x-data="{ img: image }"
                x-init="() => {
                    for(let i = 0; i <= ids.length; i++) {
                        if(img.salsah_id == ids[i]) {
                            img.selected = true
                            img.visible = true
                        }
                    }
                }"
                @click="() => {
                    let contains = false;
                    selection.forEach(el => {
                        if(el.salsah_id == img.salsah_id) {
                            contains = true;
                        }
                    })
                    if(! contains) {
                        selection.push(img)
                        img.selected = true
                    }
                }">
                    <img class="w-full result" :class="img.visible ? '' : 'p-2 px-4 text-xs'"
                        :alt="img.title" :name="img.title" :src="img.visible && img.src" :data-src="img.src">
                    <div x-show="show_details">
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
        </template>
    </div>

    <hr class="my-6">

    <h2 class="text-2xl text-bold">Selection</h2>

    <div class="grid grid-flow-row gap-4 mb-8" :class="'grid-cols-'+grid_col_size">
        <template x-for="image in selection" :key="image.id">
            <div class=" border border-blue-600 overflow-hidden"
                x-data="{ img: image }"
                @click="() => {
                    images.forEach(el => {
                        if(el.id == img.id) {
                            el.selected = false
                        }
                    })
                    selection = selection.filter(item => item !== img)
                }">
                <img class="w-full" :src="img.src"
                    :alt="img.title" :name="img.title">
                <div class="flex p-2 px-4">
                    <a class="" target="_blank"
                        :href="'https://data.dasch.swiss/resources/'+img.salsah_id"
                        @click.stop>🔗</a>
                </div>
            </div>
        </template>
    </div>

    <div class="flex bottom-8 w-full shadow-xl py-6 px-8">
        <a class="p-2 px-4 mr-2 border border-black-500 "
            :href="'/light-table?ids=' + ids.join(',')">💡 Lichttisch</a>
        <a class="p-2 px-4 border border-black-500 " 
            :href="'/map?ids=' + ids.join(',')">🗺️ Karte</a>
    </div>

    <script>

        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                images: [],
                selection: [],
                keywords: [],

                q: '',
                loading: false,

                grid_col_size: 4,
                show_details: true,

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
                                    el.src = image_call(el.collection, el.signature, el.salsah_id);

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

                get ids() {
                    let _ids = [];
                    this.selection.forEach(el => {
                        _ids.push(el.salsah_id)
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
                                    el.src = image_call(el.collection, el.signature, el.salsah_id);
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
                        if(value.length) {
                            url.searchParams.set('ids', this.ids.join(','))
                        } else {
                            url.searchParams.delete('ids')
                        }
                        history.pushState(null, document.title, url.toString())
                    });
                }
            }))
        })

        function image_call(collection, signature, salsah_id) {
            /*if(collection == 'SGV_10') {
                return `http://pia-iiif.dhlab.unibas.ch/SGV_10/${signature}.jp2/full/640,/0/default.jpg`
            } else {
                return `https://data.dasch.swiss/core/sendlocdata.php?res=${salsah_id}&qtype=full&reduce=4`
            }*/

            return `https://pia-iiif.dhlab.unibas.ch/${collection.label}/${signature}.jp2/full/640,/0/default.jpg`

            //return `https://data.dasch.swiss/core/sendlocdata.php?res=${salsah_id}&qtype=full&reduce=4`
        }

    </script>

</div>
@endsection
