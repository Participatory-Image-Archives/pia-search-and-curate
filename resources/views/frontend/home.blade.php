@extends('frontend/base')

@section('styles')
    <style>

        body {
            padding: 20px;
        }

        .item {
            position: relative;
        }
        .item img {
            width: 100%;
            background: #efefef;
        }
        .item_tools {
            display: none;
            position: absolute;
            top: 20px;
            right: 15px;
        }
        .item:hover .item_tools {
            display: inline-block;
        }
        .item_tools a {
            width: 20px;
            margin-right: 5px;
            background: white !important;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.125);
        }
        #results_wrapper .item_remove {
            display: none;
        }
        #selection_wrapper .item_add,
        #selection_wrapper .item_load_image {
            display: none;
        }
        #actions {
            position: fixed;
            bottom: 0;
            width: 100%;
            margin-bottom: 0;
            padding-right: 10px;
            background: white;
        }
        #tags_wrapper .tag {
            cursor: pointer;
        }

    </style>
@endsection

@section('content')
    <div class="columns">
        <div class="column is-two-third">
            <form action="backend.php" id="fuzzy_search" class="level">
                <div class="field has-addons level" style="margin-bottom: 0;">
                    <p class="control">
                        <input type="text" name="q" class="input q">
                    </p>
                    <p class="control">
                        <button class="button">Suchen</button>
                    </p>
                </div>
                <div class="field level">
                    <p class="control mr-1 ml-1">
                        <span class="results_count is-bold">0</span> Bild(er) 
                    </p>
                    <p>
                        <button class="show_results button" type="button">anzeigen.</button>
                    </p>
                </div>
            </form>
        
            <div id="tags_wrapper" class="mb-3"></div>
            <div class="columns is-multiline is-3" id="results_wrapper"></div>
        </div>
        <div class="column is-one-third ml-3" style="background: rgba(239, 239, 239, 0.5);">
            <form action="backend.php" id="fuzzy_search" class="level has-text-centered is-hidden">
                <div class="field level">
                    <p class="control mr-2">
                        Bilder in 
                    </p>
                    <p class="control">
                        <select name="selection_image_size" class="input selection_image_size">
                            <option value="6">kleiner</option>
                            <option value="5">normaler</option>
                            <option value="4" selected>grosser</option>
                            <option value="3">maximaler</option>
                        </select>
                    </p>
                    <p class="mr-2 ml-2">Grösse</p>
                    <p>
                        <button class="show_selection button" type="button">anzeigen.</button>
                    </p>
                </div>
            </form>
            <div class="columns is-multiline" id="selection_wrapper"></div>
        </div>
    </div>

    <div class="columns has-text-right" id="actions">
        <div class="column">
            <div>
                <button class="generate_selection button" type="button">💾 JSON</button>
                <button class="show_storyline button is-hidden" type="button">🕐 Story</button>
                <button class="show_timeline button is-hidden" type="button">📆 Timeline</button>
                <button class="show_lighttable button" type="button">💡 Lichttisch</button>
                <button class="show_map button" type="button">🗺️ Karte</button>
            </div>
        </div>
    </div>

    <div id="result_item_template" class="item column is-hidden is-size-7">
        <img src="" alt="" class="item_image">
        <div class="item_tools level">
            <a class="button is-small is-ghost is-rounded item_load_image" target="_blank">🖼️</a>
            <a class="button is-small is-ghost is-rounded item_add" target="_blank">➕</a>
            <a class="button is-small is-ghost is-rounded item_remove" target="_blank">➖</a>
            <a class="button is-small is-ghost is-rounded item_link" href="https://data.dasch.swiss/resources/" target="_blank">🔗</a>
        </div>
    </div>
@endsection

@section('scripts')
<script>

        const api_url = 'api/images';

        const results_wrapper = document.querySelector('#results_wrapper'),
              tags_wrapper = document.querySelector('#tags_wrapper'),
              selection_wrapper = document.querySelector('#selection_wrapper'),
              result_item_template = document.querySelector('#result_item_template');
        
        let image_size = 4,
            column_size = 'is-half',
            filtered_data = [],
            selection_ids = [],
            all_tags = [],
            active_tags = [],
            disabled_tags = [];

        let params = new URLSearchParams(window.location.search);

        function render_results(){
            results_wrapper.innerHTML = '';
            tags_wrapper.innerHTML = '';
            all_tags = [];

            filtered_data.forEach(el => {
                render_item(el);
            });

            all_tags.forEach(el => {
                let tag = document.createElement('span');
                tag.innerHTML = el;
                tag.classList.add('tag', 'mr-2', 'mb-1', 'is-primary');
                tags_wrapper.appendChild(tag);
            })
        }

        function render_item(el){
            let item = produce_item(el);

            item.dataset.tags = '';

            results_wrapper.appendChild(item);

            /*for(const key in el){
                if(key.includes('tag') && el[key] && !all_tags.includes(el[key])){
                    all_tags.push(el[key]);
                    item.dataset.tags = item.dataset.tags + ' ' + el[key];
                }
            }*/
        }

        function produce_item(el){
            let item = result_item_template.cloneNode(true);

            item.querySelector('.item_image').dataset.id = el.id;
            item.querySelector('.item_image').name = el.salsah_id;
            item.querySelector('.item_image').alt = el.title;
            item.querySelector('.item_link').href
                = item.querySelector('.item_link').href+el.salsah_id;

            item.id = '';
            item.classList.remove('is-hidden');
            item.classList.add('is-one-quarter');

            return item;
        }

        function filter_by_tags(){
            active_tags = [];
            disabled_tags = [];
            document.querySelectorAll('#tags_wrapper .tag').forEach((el, i) => {
                if(el.classList.contains('is-primary')){
                    active_tags.push(el.innerHTML);
                } else {
                    disabled_tags.push(el.innerHTML);
                }
            })

            results_wrapper.innerHTML = '';

            filtered_data.forEach(el => {
                let render = disabled_tags.length ? false : true;
                for(const key in el){
                    if(key.includes('tag') && el[key] && active_tags.includes(el[key])){
                        render = true;
                    }
                }
                if(active_tags.length == 0) {
                    render = true;
                }
                if(render) {
                    render_item(el);
                }
            });

            document.querySelector('#fuzzy_search .results_count').innerHTML = document.querySelectorAll('#results_wrapper .item').length
        }

        function show_results(evt){
            document.querySelectorAll('#results_wrapper .item:not(.is-hidden)').forEach((el, i) => {
                let img = el.querySelector('.item_image');
                img.src
                    = 'https://data.dasch.swiss/core/sendlocdata.php?qtype=full&reduce=5&res='+img.name;
            })
            
        }

        function show_selection(evt){
            document.querySelectorAll('#selection_wrapper .item:not(.is-hidden)').forEach((el, i) => {
                el.className = 'item column';
                el.classList.add(column_size);

                let img = el.querySelector('.item_image');
                img.src
                    = 'https://data.dasch.swiss/core/sendlocdata.php?qtype=full&reduce='+image_size+'&res='+img.name;
            })
            
        }

        function query(q){
            if(q == '') {
                alert('Suchbegriff(e) fehlen.');
                return;
            }

            let xmlhttp = new XMLHttpRequest();
                url = api_url+'?q='+q;

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    filtered_data = JSON.parse(this.responseText);
                    document.querySelector('#fuzzy_search .results_count').innerHTML = filtered_data.length;

                    render_results();
                }
            };
            xmlhttp.open('GET', url, true);
            xmlhttp.send();
        }

        function save_selection(){
            let selection = [];

            document.querySelectorAll('#selection_wrapper .item:not(.is-hidden) img').forEach((el, i) => {
                selection.push(el.name)
            });

            if(!selection.length) {
                alert('Keine Auswahl getroffen.');
                return;
            }

            let xmlhttp = new XMLHttpRequest(),
                url = api_url+'?ids='+selection.join(',');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    selected_data = JSON.parse(this.responseText);
                    console.log(selected_data);
                }
            };
            xmlhttp.open('GET', url, true);
            xmlhttp.send();
        }

        function params_to_url(){
            let refresh = window.location.protocol + "//" + window.location.host + window.location.pathname,
                query_string = '',
                search_string = document.querySelector('#fuzzy_search .q').value;

            if(search_string){
                query_string += '&q='+search_string;
            }

            if(selection_ids.length) {
                query_string += '&ids='+selection_ids.join(',')
            }

            if(search_string.length || selection_ids.length) {
                window.history.pushState({ path: refresh }, '', refresh+'?'+query_string.substring(1));
            }

        }

        function ids_to_selection(){

            selection_ids = params.get('ids').split(',');

            console.log(selection_ids)

            let xmlhttp = new XMLHttpRequest(),
                url = api_url+'?ids='+params.get('ids');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    selected_data = JSON.parse(this.responseText);

                    console.log(selected_data)

                    selected_data.forEach((el, i) => {
                        let item = produce_item(el);
                        selection_wrapper.appendChild(item);
                    });

                    show_selection();
                }
            };
            xmlhttp.open('GET', url, true);
            xmlhttp.send();
        }

        function on_tool(action){
            let selection = [];

            document.querySelectorAll('#selection_wrapper .item:not(.is-hidden) img').forEach((el, i) => {
                selection.push(el.dataset.id)
            });

            if(!selection.length) {
                alert('Keine Auswahl getroffen.');
                return;
            }

            window.location = window.location.pathname + action + '?ids=' + selection.join(',')
        }

        document.querySelector('#fuzzy_search').addEventListener('submit', evt => {
            evt.preventDefault();
            const q = document.querySelector('#fuzzy_search .q').value;
            query(q)
        })

        document.querySelector('.selection_image_size').addEventListener('change', evt => {
            image_size = evt.target.value;

            switch(image_size){
                case '5': column_size = 'is-one-quarter'; break;
                case '4': column_size = 'is-one-third'; break;
                case '3': column_size = 'is-half'; break;
                default:  column_size = 'is-one-fifth'; break;
            }
        })

        document.querySelector('.show_results').addEventListener('click', show_results);
        document.querySelector('.show_selection').addEventListener('click', show_selection);
        document.querySelector('.generate_selection').addEventListener('click', save_selection);
        document.querySelector('.show_storyline').addEventListener('click', () => {
            on_tool('storyline')
        });
        document.querySelector('.show_timeline').addEventListener('click', () => {
            on_tool('timeline')
        });
        document.querySelector('.show_lighttable').addEventListener('click', () => {
            on_tool('light-table')
        });
        document.querySelector('.show_map').addEventListener('click', () => {
            on_tool('map')
        });

        // selection processes
        results_wrapper.addEventListener('click', evt => {
            let target = evt.target,
                parent = target.parentNode.parentNode,
                img = parent.querySelector('.item_image');

            if(target.classList.contains('item_add')){
                evt.preventDefault();
                let cloned = parent.cloneNode(true)
                selection_wrapper.appendChild(cloned)
                show_selection();

                selection_ids.push(img.dataset.id);
                params_to_url();
            }

            if(target.classList.contains('item_load_image')){
                evt.preventDefault();
                img.src
                    = 'https://data.dasch.swiss/core/sendlocdata.php?qtype=full&reduce=5&res='+img.name;
            }
        })

        selection_wrapper.addEventListener('click', evt => {
            let target = evt.target,
                parent = target.parentNode.parentNode,
                img = parent.querySelector('.item_image');

            if(target.classList.contains('item_remove')){
                evt.preventDefault();
                selection_wrapper.removeChild(parent)

                const index = selection_ids.indexOf(img.dataset.id);
                if (index > -1) {
                    selection_ids.splice(index, 1);
                    params_to_url();
                }
            }
        })

        tags_wrapper.addEventListener('click', evt => {
            let target = evt.target;

            if(target.classList.contains('tag')){
                target.classList.toggle('is-primary')
            }

            filter_by_tags()
        })

        if(params.has('ids')){
            ids_to_selection();
        }

        if(params.has('q')){
            document.querySelector('#fuzzy_search .q').value = params.get('q');
            document.querySelector('#fuzzy_search .button').click();
        }

        // attempt at zipping
        /*document.querySelector('.zip_images').addEventListener('click', evt => {
            let zip = new JSZip(),
                label = document.querySelector('#fuzzy_search .q').value,
                imgs = zip.folder(label);

            document.querySelectorAll('#results_wrapper .item').forEach((el, i) => {
                let img = el.querySelector('.item_image');
            })
            
            zip.generateAsync({type:'blob'})
            .then(function(content) {
                saveAs(content, label+'.zip');
            });
        })*/
        
    </script>
@endsection
