<div class="pt-14 pb-20 pl-14 pr-4">
    <div class="relative flex items-center justify-between mb-12 ">
        <h2 class="text-4xl text-center">
            {{ $image->title }}
        </h2>
        <span class="inline-block w-10 h-10 leading-10 border border-gray-500 text-center text-xs">
            @php
                $total_count = 0;
                foreach ($image->collections as $c) {
                    $total_count += $c->maps()->count();
                    $total_count += $c->docs()->count();
                }
            @endphp
            {{ $total_count }}
        </span>
    </div>

    @if(isset($header))
    <div class="h-96 bg-center bg-contain bg-no-repeat mb-10"
        style="background-image: url('https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/960,/0/default.jpg')">
    </div>
    @endif

    <div class="mb-10">
        <span class="text-xs">View </span>
        <x-links.cta label="Similar" href="{{ route('images.similar', $image) }}"/>
        <x-links.cta label="IIIF Image API (Full Image)" href="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/max/0/default.jpg" target="_blank"/>
        <x-links.bare label="IIIF Image API (info.json)" href="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/info.json" target="_blank"/>
        <x-links.cta label="API JSON" href="{{ env('API_URL') }}images/{{ $image->id }}" target="_blank"/>
        <x-links.cta label="SALSAH" href="https://data.dasch.swiss/resources/{{ $image->salsah_id }}" target="_blank"/>
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
        -
        @endforelse
    </div>
    
    <hr class="my-4">

    <div>
        <h3 class="mb-1 text-xs">People</h3>
        <div class="mb-2">
            @forelse ($image->people as $person)
                @if ($person->name)
                    <x-links.default href="/?person={{ $person->id }}" :label="$person->name" class="mb-2"/>
                @endif
            @empty
            –
            @endforelse
        </div>
        <h3 class="mb-1 text-xs">Dates</h3>
        <div class="flex mb-2">
            @forelse ($image->dates as $date)
                <div>
                    @if ($date->date)
                        @php
                            // preparing string for display
                            $display_format = 'd. M Y';

                            if($date->accuracy == 2) {
                                $display_format = 'M Y';
                            } else if ($date->accuracy == 3) {
                                $display_format = 'Y';
                            }

                            $start_date = date($display_format, strtotime($date->date));
                            $end_date = date($display_format, strtotime($date->date));

                            if($date->end_date) {
                                $end_date = date($display_format, strtotime($date->end_date));
                                $date_string = $start_date . ' - ' . $end_date;
                            } else {
                                $date_string = $start_date;
                            }

                            // preparing string for search
                            $search_start_date = date('Y-m-d', strtotime($date->date));

                            if($date->end_date) {
                                $search_end_date = date('Y-m-d', strtotime($date->end_date));
                            } else {
                                $search_end_date = $search_start_date;
                            }

                            if($date->accuracy == 2) {
                                $search_end_date = date('Y-m-t', strtotime($end_date));
                            } else if ($date->accuracy == 3) {
                                $search_end_date = $end_date . '-12-31';
                            }
                            
                            $search_string = $search_start_date.','.$search_end_date;

                        @endphp
                        <x-links.default label="{{ $date_string }}" href="/?dates={{ $search_string }}" class="mb-2 name"/>
                    @endif
                </div>
            @empty
            –
            @endforelse
        </div>
        <h3 class="mb-1 text-xs">Locations</h3>
        <div class="flex mb-2">
            @if ($image->location)
                @if ($image->location->label)
                    <x-links.default :label="$image->location->label" href="{{ route('locations.show', [$image->location]) }}" class="mb-2 name"/>
                @endif
            @else
            -
            @endforelse
        </div>
    </div>

    <div class="flex mb-10">
        <div class="w-1/3">
            <h2 class="text-xs mb-2">Used in Notes</h2>
            <div>
                <ul>
                    @foreach ($image->collections as $c)
                        @foreach ($c->docs as $doc)
                        <li class="mb-2">
                            <x-links.default :label="$doc->label" href="{{ route('docs.edit', [$doc]) }}" />
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

    <div class="flex justify-between fixed bottom-0 left-1/2 w-1/2 pl-8 py-2 pr-28 border-t leading-10 border-gray-700 bg-white">
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