@extends('base')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="flex" id="searchable-list" >
        <div class="md:fixed bg-black md:h-screen md:w-1/4 p-4 md:overflow-y-auto">
            <h2 class="text-4xl text-white mb-4">{{ $call->collection->label }}</h2>
            <div id="images" class="pb-20">
                @include('collections.partials.display-grid', ['images' => $call->collection->images, 'gridcols' => ''])
            </div>
        </div>

        <div class="md:fixed md:left-1/4 md:h-screen w-full md:w-3/4 md:pr-16 bg-white overflow-y-auto">
            <div class="p-4 md:px-14">
                <div class="relative flex items-center justify-between mb-12 ">
                    <h2 class="text-4xl text-center mb-2">
                        {{ $call->label }}
                    </h2>
                </div>

                @if($call->description)
                <div>
                    <p>{{ $call->description }}</p>
                </div>
                @endif

                <table class="w-full my-4">
                    <thead class="text-xs">
                        <tr>
                            <td class="pb-2 w-1/3">Field</td>
                            <td class="pb-2">Value</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Creator</td>
                            <td>{{ $call->creator ?? '–' }}</td>
                        </tr>
                        <tr>
                            <td>Start Date</td>
                            <td>{{ $call->start_date ? date('d. M. Y', strtotime($call->start_date)) : '–' }}</td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>{{ $call->end_date ? date('d. M. Y', strtotime($call->end_date)) : '–' }}</td>
                        </tr>
                        <tr>
                            <td>Keywords</td>
                            <td>
                                @forelse ($call->keywords as $keyword)
                                    @if ($keyword->label)
                                        <x-links.default :href="route('keywords.show', [$keyword])" :label="$keyword->label" class="mb-2"/>
                                    @endif
                                @empty
                                –
                                @endforelse
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4 mb-28">
                    @foreach ($call->collection->images as $image)
                        <div class="py-4 border-b border-gray-400 flex flex-row items-center gap-8 overflow-x-auto">
                            <img class="inline-block mr-2" width="280"
                                src="https://sipi.participatory-archives.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/360,/0/default.jpg"
                                alt="{{$image->title}}" title="{{$image->title}}">
                            <a href="{{ route('callentries.create') }}?call_id={{ $call->id }}&image_id={{ $image->id }}" class="rounded-full border border-black text-center text-4xl block" style="line-height: 80px; min-width: 80px;">+</a>
                            <div class="flex flex-row flex-shrink-0 items-end gap-2">
                                @foreach($image->callEntries->where('call_id', $call->id) as $call_entry)
                                    @foreach ($call_entry->documents as $document)
                                        <x-smart-download src="{{ storage_path('app/public/'. $document->base_path . '/' . $document->file_name) }}" target="_blank">
                                            @if(preg_match('(\.jpg|\.png|\.jpeg)', strtolower($document->file_name)) === 1)
                                            <x-smart-image src="{{ storage_path('app/public/'. $document->base_path . '/' . $document->file_name) }}" alt="{{ $document->file_name  }}" style="max-width: 360x; max-height: 360px;" data-template="orientate"/>
                                            @else
                                                <span class="inline-block py-1 px-3 text-xs rounded-full border border-black hover:bg-black hover:text-white">{{ $document->file_name  }}</span>    
                                            @endif
                                        </x-smart-download>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mb-28">
                    <h2 class="text-3xl mb-4">Timeline</h2>
                    @foreach ($call->callEntries as $call_entry)
                        <div class="{{ $loop->even ? 'ml-auto' : '' }} w-5/6 md:w-3/4 border border-grey-500 rounded-xl p-4 mb-8 shadow-xl relative">
                            <h3 class="text-xl">{{ $call_entry->label }}</h3>
                            <span class="block text-xs italic mb-2">By {{ $call_entry->creator }}, {{ date('d. M. Y', strtotime($call_entry->created_at)) }}</span>
                            <p>{{ $call_entry->comment ?? '-' }}</p>
                            <div class="mt-2">
                                <ul>
                                    @foreach ($call_entry->documents as $document)
                                    <li class="mb-2">
                                        <x-smart-download src="{{ storage_path('app/public/'. $document->base_path . '/' . $document->file_name) }}" target="_blank">
                                            @if(preg_match('(\.jpg|\.png|\.jpeg)', strtolower($document->file_name)) === 1)
                                            <x-smart-image src="{{ storage_path('app/public/'. $document->base_path . '/' . $document->file_name) }}" alt="{{ $document->file_name  }}" width="400px" data-template="orientate"/>
                                            @else
                                                <span class="inline-block py-1 px-3 text-xs rounded-full border border-black hover:bg-black hover:text-white">{{ $document->file_name  }}</span>    
                                            @endif
                                        </x-smart-download>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mt-4 text-xs">
                                @foreach ($call_entry->keywords as $entry_keyword)
                                    <a href="/?keyword={{ $entry_keyword->id }}" class="mr-2 italic hover:underline">{{ $entry_keyword->label }}</a>
                                @endforeach
                            </div>
                            <form action="{{ route('callentries.destroy', [$call_entry]) }}" method="post" class="inline-block absolute top-2 right-2">
                                @csrf
                                @method('delete')

                                <x-buttons.delete label="x"/>
                            </form>
                       </div>
                    @endforeach
                </div>


                <div class="flex flex-col md:flex-row justify-between md:fixed bottom-0 md:left-1/4 md:w-3/4 md:p-4 pb-20 md:pr-20 md:pb-4 border-t leading-10 border-gray-700 bg-white">
                    <a class="hover:underline" href="{{ route('calls.edit', [$call]) }}">Edit call</a>
                    <a class="hover:underline" href="{{ route('collections.show', [$call->collection_id]) }}">View collection</a>
                    <form action="{{ route('calls.destroy', [$call]) }}" method="post" class="inline-block">
                        @csrf
                        @method('delete')
    
                        <x-buttons.delete/>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <aside id="sidebar"
        x-data="{expand_collections: false}"
        @mouseleave="expand_collections = false;"
        class="flex fixed top-0 right-0 transform transition min-h-screen shadow-2xl z-50 print-hidden">
        
        <livewire:collections-aside />
    </aside>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('node_modules/list.js/dist/list.min.js') }}"></script>
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            var searchable_list = new List('searchable-list', {
                valueNames: ['title', 'signature', 'oldnr', 'tags']
            });
        });

    </script>
@endsection
