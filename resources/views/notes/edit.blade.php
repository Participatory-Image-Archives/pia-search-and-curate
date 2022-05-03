@extends('base')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@300&display=swap" rel="stylesheet">
<style>
    * {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    #document-markdown,
    .EasyMDEContainer .CodeMirror {
        font-family: 'DM Mono', monospace;
    }

    #document-rendered h1 {
        font-size: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    #document-rendered img {
        margin: 0 auto;
    }

    /* some editor fixes */
    .EasyMDEContainer {
        overflow: scroll;
        min-height: calc(100% - 70px);
        max-height: calc(100% - 70px);
    }

    .EasyMDEContainer .CodeMirror-scroll {
        min-height: calc(100% - 60px) !important;
        border: 0;
    }

    .EasyMDEContainer .editor-toolbar {
        border: 0;
    }

    .EasyMDEContainer .editor-toolbar.disabled-for-preview {
        opacity: 0.25;
    }

    .EasyMDEContainer .editor-preview {
        background: white;
        font-family: 'Apercu', sans;
    }

    .EasyMDEContainer .editor-preview li:before {
        content: '–';
        margin-right: 3px;
    }

    .EasyMDEContainer .editor-preview .image-reference {
        display: inline-block;
        position: relative;
        top: 6px;
        overflow: hidden;
        margin: 0 2px;
        width: 24px;
        height: 24px;
        background: black;
        border-radius: 50%;
        color: rgba(0, 0, 0, 0);
    }

</style>
@endsection

@section('content')
<div class="flex max-h-screen min-h-screen" x-data="app">
    <div class="bg-black p-4 pt-14 max-h-screen overflow-scroll transition-all"
        :class="minimize_collection ? 'w-auto' : 'w-1/4'">
        <div class="grid grid-cols-1 gap-4" id="images">
            @foreach ($collection->images as $image)
            <img class="inline-block mr-2 w-full"
                src="https://sipi.participatory-archives.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/360,/0/default.jpg"
                alt="{{$image->title}}" title="{{$image->title}}" @dragstart="dragstart">
            @endforeach
        </div>
    </div>

    <div class="relative overflow-y-auto bg-gray-100 transition-all" :class="minimize_collection ? 'w-12' : 'w-1/2'">
        <button class="absolute top-2 right-2" @click="minimize_collection = ! minimize_collection">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div x-show="! minimize_collection">
            @include('collections.partials.collection-info')
        </div>
        <div x-show="minimize_collection">
            <h2 class="text-4xl mt-14 ml-1 whitespace-nowrap" style="text-orientation: upright; writing-mode: vertical-rl;">
                {{ $collection->label }}</h2>
        </div>
    </div>

    <div class="h-screen overflow-hidden transition-all pb-16" :class="minimize_collection ? 'w-2/3' : 'w-1/4'">
        <form action="{{ route('notes.update', [$note]) }}" method="post" x-ref="noteform" class="h-full">
            @csrf
            @method('patch')

            <input type="text" name="label" value="{{ $note->label }}" class="text-4xl p-4 w-full border-b border-gray-300">

            <textarea name="content" id="document-markdown" class="outline-none w-full pb-20" @drop="drop"
                @dragover="dragover">{{ $note->content }}</textarea>
            
        </form>

        <div class="flex justify-between absolute bottom-0 px-8 py-2 border-t leading-10 border-gray-700 bg-white"
            :class="minimize_collection ? 'w-2/3' : 'w-1/4'">
            <button type="button" class="hover:underline" @click="$refs.noteform.submit()">Save</button>

            <form action="{{ route('notes.destroy', [$note]) }}" method="post" class="inline-block">
                @csrf
                @method('delete')

                <x-buttons.delete/>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.data('app', () => ({

            minimize_collection: false,

            // methods
            init() {

            },

            dragstart(evt) {
                let img = evt.target;
                evt.dataTransfer.setData('text', `[${img.alt}](${img.src})`);
            },

            dragover(evt) {
                evt.preventDefault();
            },

            drop(evt) {

            }
        }));
    });

    document.addEventListener('DOMContentLoaded', () => {

        let show_circles = false;

        let editor = new EasyMDE({
            element: document.getElementById('document-markdown'),
            autofocus: true,
            spellChecker: false,
            placeholder: "Start here…",
            renderingConfig: {
                sanitizerFunction: (rendered_html) => {
                    let html_wrapper = document.createElement('div');

                    html_wrapper.innerHTML = rendered_html;

                    html_wrapper.querySelectorAll('h1').forEach(el => {
                        el.classList.add('text-4xl', 'mb-4', 'text-bold');
                    });

                    html_wrapper.querySelectorAll('h2').forEach(el => {
                        el.classList.add('text-2xl', 'mb-2', 'text-bold');
                    });

                    html_wrapper.querySelectorAll('h3').forEach(el => {
                        el.classList.add('text-xl', 'text-bold');
                    });

                    html_wrapper.querySelectorAll('p, ul').forEach(el => {
                        el.classList.add('mt-1', 'mb-2');
                    });

                    html_wrapper.querySelectorAll('a').forEach(el => {
                        if (el.href.indexOf('iiif') !== -1) {
                            if (!show_circles) {
                                let image = document.createElement('img');
                                image.src = `${el.href}`;
                                el.parentNode.replaceChild(image, el);
                            } else {
                                el.classList.add('image-reference');
                                el.href = `${el.href}`;
                            }
                        }

                    });

                    return html_wrapper.innerHTML;
                }
            },
            toolbar: [
                'heading',
                'bold',
                'italic',
                '|',
                'unordered-list',
                'ordered-list',
                'quote',
                '|',
                'link',
                'image',
                'table',
                '|',
                'preview',
                {
                    name: "circles",
                    action: (editor) => {
                        show_circles = !show_circles;

                        if (show_circles) {
                            let circle_button = document.querySelector('.fa-circle-thin');
                            circle_button.classList.remove('fa-circle-thin');
                            circle_button.classList.add('fa-circle');
                        } else {
                            let circle_button = document.querySelector('.fa-circle');
                            circle_button.classList.remove('fa-circle');
                            circle_button.classList.add('fa-circle-thin');
                        }
                    },
                    className: "fa fa-circle-thin",
                    title: "Show images as circles",
                },
                'fullscreen',
                'side-by-side',
                'guide'
            ]
        });
    })

</script>
@endsection
