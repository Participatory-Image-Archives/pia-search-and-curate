@extends('base')

@section('content')
<div class="bg-gray-100 min-h-screen" x-data="{cols: 3}">
    <div class="flex" id="searchable-list" >
        <div class="fixed h-screen w-1/2 overflow-hidden">
        </div>

        <div class="fixed left-1/2 h-screen w-1/2 pr-36 bg-white overflow-y-auto">
            <div class="pt-14 pb-20 pl-14 pr-4">
                <div class="relative flex items-center justify-between mb-12 ">
                    <h2 class="text-4xl text-center">
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
                                        <x-links.default href="/?keyword={{ $keyword->id }}" :label="$keyword->label" class="mb-2"/>
                                    @endif
                                @empty
                                –
                                @endforelse
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="my-4">
                    @foreach ($call->call_entries as $call_entry)
                        <div class="{{ $loop->even ? 'ml-auto' : '' }} w-3/4 border border-grey-500 rounded-xl p-4 mb-8 shadow-xl">
                            <h3 class="text-xl">{{ $call_entry->label }}</h3>
                            <span class="block text-xs italic mb-2">By {{ $call_entry->creator }}, {{ date('d. M. Y', strtotime($call_entry->created_at)) }}</span>
                            <p>{{ $call_entry->comment ?? '-' }}</p>
                            <div class="mt-2">
                                <ul>
                                    @foreach ($call_entry->documents as $document)
                                    <li class="mb-2">
                                        <x-links.default :label="$document->label"
                                            href="/{{ 'storage/' . $document->base_path . '/' . $document->file_name }}" />
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-700 bg-white">
                    <a class="hover:underline" href="{{ route('calls.edit', [$call]) }}">Edit call</a>
                    <a class="hover:underline" href="{{ route('callentries.create') }}?call_id={{ $call->id }}">Add entry</a>
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
