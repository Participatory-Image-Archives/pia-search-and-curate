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
        x-data="{ images: [], selection: [],
                api_url: 'api/images', q: '', ids: [] }"
        x-init="() => {
            params = new URLSearchParams(window.location.search)
            

            if(params.has('q')) {
                q = params.get('q')
                fetch('api/images?q='+q)
                    .then(response => response.json())
                    .then(data => images = data)
            }

            if(params.has('ids')) {
                ids = params.get('ids').split(',');
                fetch('api/ids?ids='+ids.join(','))
                    .then(response => response.json())
                    .then(data => selection = data)
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

        <div class="columns pb-5">
            <div class="column is-two-third">
                <div class="card">
                    <div class="card-content">
                        <form action="backend.php" id="fuzzy_search" class="level">
                            <div class="field has-addons level" style="margin-bottom: 0;">
                                <p class="control">
                                    <input type="text" name="q" class="input q"
                                        x-model="q">
                                </p>
                                <p class="control">
                                    <button class="button" @click.prevent="images = await (await fetch(api_url+'?q='+q)).json()">Suchen</button>
                                </p>
                            </div>
                            <div class="field level">
                                <p class="control mr-1 ml-1">
                                    <span class="results_count is-bold" x-text="images.length"></span> Bild(er) 
                                </p>
                                <p>
                                    <button class="show_results button" type="button"
                                        @click="() => {
                                            document.querySelectorAll('.card.result').forEach((el, i) => {
                                                let img = el.querySelector('img');
                                                img.src = 'https://data.dasch.swiss/core/sendlocdata.php?res='+img.name+'&qtype=full&reduce=4';
                                            })
                                        }">anzeigen.</button>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            
                <div id="tags_wrapper" class="mb-3"></div>

                <div class="columns is-multiline is-3" id="results_wrapper">
                    <template x-for="image in images" :key="image.id">
                        <div class="item column is-one-quarter is-size-7"
                            x-data="{ img: image, src: '', selected: false }"
                            x-init="() => {
                                for(let i = 0; i <= ids.length; i++) {
                                    if(img.salsah_id == ids[i]) {
                                        selected = true
                                        src = 'https://data.dasch.swiss/core/sendlocdata.php?res='+img.salsah_id+'&qtype=full&reduce=4'
                                    }
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
                                    selected = ! selected
                                }
                            }">
                            <div class="card result" :class="selected && 'selected'">
                                <img class="item_image"
                                    :alt="img.title" :name="img.salsah_id" :data-id="img.salsah_id" :src="src">
                                <div>
                                    <a class="button is-small is-ghost is-rounded item_load_image"
                                        @click.stop="src = 'https://data.dasch.swiss/core/sendlocdata.php?res='+image.salsah_id+'&qtype=full&reduce=4'">🖼️</a>
                                    <a class="button is-small is-ghost is-rounded item_link" target="_blank"
                                        :href="'https://data.dasch.swiss/resources/'+img.salsah_id"
                                        @click.stop>🔗</a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <hr>

                <div class="columns is-multiline is-3" id="results_wrapper">
                    <template x-for="image in selection" :key="image.id">
                        <div class="item column is-one-quarter is-size-7"
                            x-data="{ img: image }"
                            @click="() => {
                                for(let i = 0; i <= ids.length; i++) {
                                    if(img.salsah_id == ids[i]) {
                                        ids.splice(i, 1)
                                    }
                                }
                                selection = selection.filter(item => item !== img)
                            }">
                            <div class="card selected">
                                <img class="item_image" :src="'https://data.dasch.swiss/core/sendlocdata.php?res='+img.salsah_id+'&qtype=full&reduce=4'"
                                    :alt="img.title" :name="img.id" :data-id="img.id">
                                <div>
                                    <a class="button is-small is-ghost is-rounded item_link" target="_blank"
                                        :href="'https://data.dasch.swiss/resources/'+img.salsah_id"
                                        @click.stop>🔗</a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="columns has-text-right" id="actions">
            <div class="column">
                <div>
                    <a class="show_lighttable button"
                        :href="'/light-table?ids=' + ids.join(',')">💡 Lichttisch</a>
                    <a class="show_map button" 
                        :href="'/map?ids=' + ids.join(',')">🗺️ Karte</a>
                </div>
            </div>
        </div>

    </div>
@endsection
