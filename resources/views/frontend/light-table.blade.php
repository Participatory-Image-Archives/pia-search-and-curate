@extends('base')

@section('styles')
<style>

    html {
        overflow-x: scroll;
    }
    body {
        padding: 20px;
        min-width: 200vw;
        min-height: 200vh;
    }
    .draggable {
        position: absolute;
        width: 300px;
        margin: 20px;
        touch-action: none;
        user-select: none;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);
    }
    .draggable img {
        display: inline-block;
        width: 100%;
        height: auto;
        
        box-shadow: 0 0 0 rgba(0, 0, 0, 0);
        transition: 0.25s;
    }
    .dragging img {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);
    }

</style>
@endsection

@section('content')
    <div id="light-table"></div>
@endsection

@section('scripts')
    <script src="node_modules/interactjs/dist/interact.min.js"></script>
    <script>

        const api_url = 'api/ids',
              params = new URLSearchParams(window.location.search);

        let table = document.querySelector('#light-table');

        function render_table(selection){
            if(!selection.length) {
                alert('Keine Auswahl getroffen.');
                return;
            }

            let xmlhttp = new XMLHttpRequest(),
                url = api_url+'/?ids='+selection.join(',');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    selected_data = JSON.parse(this.responseText);

                    let body = document.querySelector('body');

                    window.scrollTo(body.offsetWidth/4, body.offsetHeight/4);

                    selected_data.forEach((el, i) => {
                        let item = document.createElement('div'),
                            img = document.createElement('img');

                        item.classList.add('draggable');
                        img.src = 'https://data.dasch.swiss/core/sendlocdata.php?qtype=full&reduce=3&res='+el.salsah_id;

                        item.dataset.x = 0;
                        item.dataset.y = 0;

                        item.style.left = body.offsetWidth/3.5 + Math.floor(Math.random()*body.offsetWidth/3.5)+'px';
                        item.style.top = body.offsetHeight/3.5 + Math.floor(Math.random()*body.offsetHeight/3.5)+'px';
                        
                        
                        item.appendChild(img)
                        table.appendChild(item);

                        img.addEventListener('load', evt => {
                            let target = event.target;
                            target.parentNode.style.height = event.target.height + 'px';
                        })
                    });
                    
                    interact('.draggable')
                        .draggable({
                            listeners: {
                                start (event) {
                                    var target = event.target;
                                    target.parentNode.appendChild(target);
                                    target.classList.add('dragging');
                                },
                                move (event) {
                                    event.target.dataset.x = parseInt(event.target.dataset.x) + event.dx
                                    event.target.dataset.y = parseInt(event.target.dataset.y) + event.dy

                                    event.target.style.transform =
                                        'translate('+event.target.dataset.x+'px, '+event.target.dataset.y+'px)'
                                },
                                end(event) {
                                    var target = event.target;
                                    target.classList.remove('dragging');
                                }
                            },
                            intertia: true,
                            modifiers: [
                                interact.modifiers.snap({
                                    targets: [
                                        interact.snappers.grid({ x: 30, y: 30 })
                                    ],
                                    range: Infinity,
                                    relativePoints: [ { x: 0, y: 0 } ]
                                }),
                            ],
                        })
                        .resizable({
                            edges: { left: true, right: true, top: true, bottom: true },
                            
                            listeners: {
                                start (event) {
                                    var target = event.target;
                                    target.parentNode.appendChild(target);
                                },
                                move (event) {
                                    var target = event.target
                                    var x = (parseFloat(target.getAttribute('data-x')) || 0)
                                    var y = (parseFloat(target.getAttribute('data-y')) || 0)

                                    // update the element's style
                                    target.style.width = event.rect.width + 'px'
                                    target.style.height = event.rect.height + 'px'

                                    // translate when resizing from top or left edges
                                    x += event.deltaRect.left
                                    y += event.deltaRect.top

                                    target.style.transform = 'translate(' + x + 'px,' + y + 'px)'

                                    target.setAttribute('data-x', x)
                                    target.setAttribute('data-y', y)
                                }
                            },
                            modifiers: [
                            // keep the edges inside the parent

                                // minimum size
                                interact.modifiers.restrictSize({
                                    min: { width: 100, height: 50 }
                                }),

                                interact.modifiers.snap({
                                    targets: [
                                        interact.snappers.grid({ x: 30, y: 30 })
                                    ],
                                    range: Infinity,
                                    relativePoints: [ { x: 0, y: 0 } ]
                                }),
                                interact.modifiers.aspectRatio({
                                    // The equalDelta option replaces the old resize.square option
                                    preserveAspectRatio: true,
                                }),
                            ],

                            inertia: true
                        })
                }
            };
            xmlhttp.open('GET', url, true);
            xmlhttp.send();
        }

        render_table(params.get('ids').split(','));

    </script>
@endsection
