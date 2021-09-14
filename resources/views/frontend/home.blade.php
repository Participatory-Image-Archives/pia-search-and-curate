@extends('frontend/base')

@section('styles')
    <style>

        body {
            padding: 20px;
        }

        
        .item .card {
            border: 2px solid transparent;
            cursor: pointer;
        }
        .item .card.selected {
            border: 2px solid blue;
        }
        .item .card img {
            width: 100%;
        }
        #actions {
            position: fixed;
            bottom: 0;
            width: 100%;
            margin-bottom: 0;
            padding-right: 10px;
            background: white;
        }

    </style>
@endsection

@section('content')
<div 
    x-data="{ images: [], selection: [], tags: [], threshold: 20,
            api_url: 'api/images', q: '', ids: [] }"
    x-init="() => {
        params = new URLSearchParams(window.location.search)
        
        if(params.has('q')) {
            q = params.get('q')
            if(q.length >= 3) {
                fetch('api/images?q='+q)
                    .then(response => response.json())
                    .then(data => {
                        images = data
                        images.forEach((el, i) => {
                            el.visible = false
                            el.selected = false
                            el.src = image_call(el.collection, el.signature, el.salsah_id)
                        })
                    })
            }
        }

        if(params.has('ids')) {
            ids = params.get('ids').split(',');
            fetch('api/ids?ids='+ids.join(','))
                .then(response => response.json())
                .then(data => {
                        selection = data
                        selection.forEach((el, i) => {
                            el.visible = true
                            el.selected = true
                            el.src = image_call(el.collection, el.signature, el.salsah_id)
                        })
                    })
        }

        const url = new URL(window.location.href)

        $watch('q', (value) => {
            url.searchParams.set('q', value)
            history.pushState(null, document.title, url.toString())
        });

        $watch('ids', (value) => {
            url.searchParams.set('ids', value.join(','))
            history.pushState(null, document.title, url.toString())
        });
    }">

        <div class="shadow-xl py-6 px-8 flex">
            <form action="/api/images" id="fuzzy_search" class="flex flex-1">
                <div class="" style="margin-bottom: 0;">
                    <input type="text" name="q" class="p-2 px-3 mr-2 border border-black-500 rounded focus:outline-none" x-model="q">
                    <button class="p-2 px-4 mr-2 border border-black-500 rounded" @click.prevent="() => {
                        if(q.length >= 3) {
                            fetch('api/images?q='+q)
                                .then(response => response.json())
                                .then(data => {
                                    images = data
                                    images.forEach((el, i) => {
                                        el.visible = false
                                        el.selected = false
                                        el.src = image_call(el.collection, el.signature, el.salsah_id)
                                    })
                                })
                        } else {
                            alert('Suchanfrage muss länger sein.')   
                        }
                    }">Suchen</button>
                </div>
            </form>
            <div class="flex flex-1 justify-end">
                <p class="p-2">
                    <span class="results_count is-bold" x-text="images.length"></span> Bild(er) 
                </p>
                <p>
                    <button class="p-2 px-4 mr-2 border border-black-500 rounded" type="button"
                        @click="() => {
                            images.forEach(el => {
                                el.visible = true
                            })
                        }">anzeigen.</button>
                </p>
            </div>
        </div>
    
        <div id="tags_wrapper" class="mb-3">
            <template x-for="tag in tags" :key="tag.id">
                <span class="is-size-7" :text="tag.label"></span>
            </template>
        </div>

        <div class="grid grid-flow-row grid-cols-4 gap-4">
            <template x-for="image in images" :key="image.id">
                <div class="rounded overflow-hidden border" :class="img.selected && 'border-blue-600'"
                    x-data="{ img: image }"
                    x-init="() => {
                        for(let i = 0; i <= ids.length; i++) {
                            if(img.salsah_id == ids[i]) {
                                img.selected = true
                                img.visible = true
                            }
                        }
                        if(images.length < threshold) {
                            img.visible = true
                        }
                    }"
                    @click="() => {
                        let contains = false;
                        for(let i = 0; i <= ids.length; i++) {
                            if(img.salsah_id == ids[i]) {
                                contains = true;
                            }
                        }
                        if(! contains) {
                            ids.push(img.salsah_id)
                            selection.push(img)
                            img.selected = ! img.selected
                        }
                    }">
                        <img class="w-full result"
                            :alt="img.title" :name="img.title" :src="img.visible ? img.src : ''">
                        <div class="flex p-2 px-4">
                            <button class="cursor-pointer mr-2"
                                @click.stop="img.visible = true">🖼️</button>
                            <a class="" target="_blank"
                                :href="'https://data.dasch.swiss/resources/'+img.salsah_id"
                                @click.stop>🔗</a>
                        </div>
                </div>
            </template>
        </div>

        <hr class="my-6">

        <div class="grid grid-flow-row grid-cols-6 gap-4 mb-8" >
            <template x-for="image in selection" :key="image.id">
                <div class="rounded border border-blue-600 overflow-hidden"
                    x-data="{ img: image }"
                    @click="() => {
                        images.forEach(el => {
                            if(el.id == img.id) {
                                el.selected = false
                            }
                        })
                        for(let i = 0; i <= ids.length; i++) {
                            if(img.salsah_id == ids[i]) {
                                ids.splice(i, 1)
                            }
                        }
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
            <a class="p-2 px-4 mr-2 border border-black-500 rounded"
                :href="'/light-table?ids=' + ids.join(',')">💡 Lichttisch</a>
            <a class="p-2 px-4 border border-black-500 rounded" 
                :href="'/map?ids=' + ids.join(',')">🗺️ Karte</a>
        </div>

        <script>

            function image_call(collection, signature, salsah_id) {
                /*if(collection == 'SGV_10') {
                    return `http://pia-iiif.dhlab.unibas.ch/SGV_10/${signature}.jp2/full/640,/0/default.jpg`
                } else {
                    return `https://data.dasch.swiss/core/sendlocdata.php?res=${salsah_id}&qtype=full&reduce=4`
                }*/
                return `https://data.dasch.swiss/core/sendlocdata.php?res=${salsah_id}&qtype=full&reduce=4`
            }

        </script>

</div>
@endsection
