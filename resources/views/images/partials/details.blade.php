<div class="p-4 md:p-14">
    <div class="relative flex items-center justify-between mb-12 ">
        <h2 class="text-4xl text-center">
            {{ $image->title }}
        </h2>
        <span class="inline-block w-10 h-10 leading-10 border border-gray-500 text-center text-xs">
            @php
                $total_count = 0;
                foreach ($image->collections as $c) {
                    $total_count += $c->maps()->count();
                    $total_count += $c->notes()->count();
                }
            @endphp
            {{ $total_count }}
        </span>
    </div>

    @if(isset($header))
    <div class="h-96 bg-center bg-contain bg-no-repeat mb-10"
        style="background-image: url('https://sipi.participatory-archives.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/560,/0/default.jpg')">
    </div>
    @endif

    <div class="mb-10">
        <span class="text-xs">View </span>
        <ul class="list">
            <li><x-links.bare label="Similar" href="{{ route('images.similar', $image) }}"/></li>
            <li>
                <span class="text-xs">IIIF Image API: </span>
                <x-links.bare label="full image" href="https://sipi.participatory-archives.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/max/0/default.jpg" target="_blank"/>, 
                <x-links.bare label="info.json" href="https://sipi.participatory-archives.ch/{{$image->base_path}}/{{$image->signature}}.jp2/info.json" target="_blank"/>
            </li>
            <li>
                <span class="text-xs">IIIF Presentation API: </span>
                <x-links.bare label="manifest.json" href="https://iiif.participatory-archives.ch/{{$image->id}}/manifest.json" target="_blank"/>
            </li>
            <li><x-links.bare label="JSON API" href="https://data.participatory-archives.ch/api/v1/images/{{ $image->id }}" target="_blank"/></li>
            <li><x-links.bare label="SALSAH" href="https://data.dasch.swiss/resources/{{ $image->salsah_id }}" target="_blank"/></li>
        </ul>
    </div>

    <table class="w-full">
        <thead class="text-xs">
            <tr>
                <td class="pb-2 w-1/3">Field</td>
                <td class="pb-2">Value</td>
            </tr>
        </thead>
        <tr>
            <td>SALSAH ID</td>
            <td>{{ $image->salsah_id ?? '–' }}</td>
        </tr>
        <tr>
            <td>Old Nr</td>
            <td>{{ $image->oldnr ?? '–' }}</td>
        </tr>
        <tr>
            <td>Signature</td>
            <td>{{ $image->signature ?? '–' }}</td>
        </tr>
        <tr>
            <td>Original Title</td>
            <td>{{ $image->original_title ?? '–' }}</td>
        </tr>
        <tr>
            <td>Sequence Number</td>
            <td>{{ $image->sequence_number ?? '–' }}</td>
        </tr>
    </table>

    <hr class="my-4">

    <table class="w-full">
        <tr>
            <td class="w-1/3">Verso</td>
            <td>
                @if ($image->verso)
                    <a href="{{ route('images.show', [$id => $image->verso]) }}">Verso</a>
                @else
                –
                @endif
            </td>
        </tr>
        <tr>
            <td>Object Type</td>
            <td>
                {{ $image->objectType->label ?? '–' }}
            </td>
        </tr>
        <tr>
            <td>Model Type</td>
            <td>
                {{ $image->modelType->label ?? '–' }}
            </td>
        </tr>
        <tr>
            <td>Format</td>
            <td>
                {{ $image->format->label ?? '–' }} / 
                {{ $image->format->comment ?? '–' }}
            </td>
        </tr>
    </table>
    
    <hr class="my-4">

    <div>
        <h3 class="mb-1 text-xs">Keywords</h3>
        <div class="mb-2">
            @forelse ($image->keywords as $keyword)
                @if ($keyword->label)
                    <x-links.default href="/?keyword={{ $keyword->id }}" :label="$keyword->label" class="mb-2"/>
                @endif
            @empty
            –
            @endforelse
        </div>
        <h3 class="mb-1 text-xs">Machine-Detections</h3>
        <div class="mb-2">
            @php
                $detection_labels = [];
            @endphp
            @forelse ($image->detections as $detection)
                @if (!in_array($detection->class->label, $detection_labels))
                    @php
                        $detection_labels[] = $detection->class->label;
                    @endphp
                    @if($detection->score > 0.8)
                        <x-links.default href="/?detection={{ $detection->class->id }}" :label="$detection->class->label" class="mb-2"/>
                    @endif
                @endif
            @empty
            –
            @endforelse
        </div>
       
        <h3 class="mb-1 text-xs">Collections</h3>
        <div class="mb-2">
            @forelse ($image->collections as $collection)
                @if ($collection->label)
                    @if($collection->origin != 'salsah')
                        <x-links.default :href="route('collections.show', [$collection])" :label="$collection->label" class="mb-2"/>
                    @else
                        <span class="inline-block py-1 px-3 text-xs rounded-full border border-black">{{ $collection->label }}</span>
                    @endif
                @endif
            @empty
            –
            @endforelse
        </div>
    </div>
    
    <hr class="my-4">

    <div>
        <h3 class="mb-1 text-xs">Comments</h3>
        @forelse ($image->comments as $comment)
            @if ($comment->comment)
                <p class="mb-2 text-sm">– {{ $comment->comment }}</p>
            @endif
        @empty
        &mdash;
        @endforelse
    </div>
    
    <hr class="my-4">

    <div>
        <h3 class="mb-1 text-xs">Copyright</h3>
        <div class="mb-2">
            @if ($image->copyright)
                <x-links.default href="/?agent={{ $image->copyright->id }}" :label="$image->copyright->name" class="mb-2"/>
            @else
                &mdash;
            @endif
        </div>
        <h3 class="mb-1 text-xs">License</h3>
        <div class="mb-2">
            @if ($image->license)
                <x-links.default :href="$image->license" :label="$image->license" class="mb-2" target="_blank"/>
            @else
                &mdash;
            @endif
        </div>
        <h3 class="mb-1 text-xs">Agents - people and institutions</h3>
        <div class="mb-2">
            @forelse ($image->agents as $agent)
                @if ($agent->name)
                    <x-links.default href="/?agent={{ $agent->id }}" :label="$agent->name" class="mb-2"/>
                @endif
            @empty
            &mdash;
            @endforelse
        </div>
        <h3 class="mb-1 text-xs">Date</h3>
        <div class="flex mb-2">
            @if ($image->date)
                <div>
                    @if ($image->date->date)
                        @php
                            // preparing string for display
                            $display_format = 'd. M Y';

                            if($image->date->accuracy == 2) {
                                $display_format = 'M Y';
                            } else if ($image->date->accuracy == 3) {
                                $display_format = 'Y';
                            }

                            $start_date = date($display_format, strtotime($image->date->date));

                            if($image->date->end_date) {
                                $end_date = date($display_format, strtotime($image->date->end_date));
                                $date_string = $start_date . ' - ' . $end_date;
                            } else {
                                $date_string = $start_date;
                            }

                            // preparing string for search
                            $search_start_date = date('Y-m-d', strtotime($image->date->date));
                            $search_end_date = $search_start_date;

                            if($image->date->end_date) {
                                $search_end_date = date('Y-m-d', strtotime($image->date->end_date));
                            } else {
                                $search_end_date = $search_start_date;
                            }
                         
                            if($image->date->end_date) {
                                if($image->date->accuracy == 2) {
                                    $search_end_date = date('Y-m-t', strtotime($end_date));
                                } else if ($image->date->accuracy == 3) {
                                    $search_end_date = $end_date . '-12-31';
                                }
                            }
                            
                            $search_string = 'from='.$search_start_date.'&to='.$search_end_date;

                        @endphp
                        <x-links.default label="{{ $date_string }}" href="/?{{ $search_string }}" class="mb-2 name"/>
                    @endif
                </div>
            @else
            &mdash;
            @endif
        </div>
        <h3 class="mb-1 text-xs">Place</h3>
        <div class="flex mb-2">
            @if ($image->place)
                @if ($image->place->label)
                    <x-links.default :label="$image->place->label" href="{{ route('places.show', [$image->place]) }}" class="mb-2 name"/>
                @endif
            @else
            &mdash;
            @endforelse
        </div>
    </div>

    <div class="flex mb-10">
        <div class="w-1/3">
            <h2 class="text-xs mb-2">Used in Notes</h2>
            <div>
                <ul>
                    @foreach ($image->collections as $c)
                        @foreach ($c->notes as $note)
                        <li class="mb-2">
                            <x-links.default :label="$note->label" href="{{ route('notes.edit', [$note]) }}" />
                        </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="w-1/3">
            <h2 class="text-xs mb-2">Used in Maps</h2>
            <div>
                <ul>
                    @foreach ($image->collections as $c)
                        @foreach ($c->maps as $map)
                        <li class="mb-2">
                            <x-links.default :label="$map->label" href="{{ route('maps.images', [$map]) }}" />
                        </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between md:fixed bottom-0 md:left-1/2 md:w-1/2 md:p-4 pb-20 md:pr-20 md:pb-4 border-t leading-10 border-gray-700 bg-white">
        <div>
            @if($prev ?? false)
            <a class="p-1 px-4 rounded-full bg-black text-white" href="{{ route('images.show', [$prev]) }}?cid={{ $cid }}&iid={{ $prev->id }}" title="Show previous image from collection"><</a>
            @endif
            @if($next ?? false)
            <a class="p-1 px-4 rounded-full bg-black text-white" href="{{ route('images.show', [$next]) }}?cid={{ $cid }}&iid={{ $next->id }}" title="Show next image from collection">></a>
            @endif
        </div>
        <a class="hover:underline" href="{{ route('images.edit', [$image]) }}">Edit info</a>
        @php
            $deletable = true
        @endphp
        @foreach ($image->collections as $c)
            @if($c->origin == 'salsah')
                {{ $deletable = false }}
            @endif
        @endforeach
        @if($deletable)
        <form action="{{ route('images.destroy', [$image]) }}" method="post" class="inline-block">
            @csrf
            @method('delete')

            <x-buttons.delete/>
        </form>
        @endif
    </div>
</div>
