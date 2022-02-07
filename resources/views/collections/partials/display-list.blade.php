<div class="list print-grid print-w-full">
    @foreach ($collection->images as $image)
    <div class="flex mb-2 p-4 bg-white">
        <a href="{{ route('images.show', [$image]) }}" class="pr-2 w-1/4 print-image">
            <img class="inline-block"
                src="https://pia-iiif.dhlab.unibas.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/360,/0/default.jpg"
                alt="{{$image->title}}" title="{{$image->title}}">
        </a>
        <div class="image-meta w-3/4 pl-2">
            <table class="w-full mb-4">
                <thead class="text-xs">
                    <tr>
                        <td class="pb-2 w-1/5">Field</td>
                        <td class="pb-2">Value</td>
                    </tr>
                </thead>
                <tr>
                    <td>Title</td>
                    <td class="title">{{ $image->title ?? '–' }}</td>
                </tr>
                <tr>
                    <td>Signature</td>
                    <td class="signature">{{ $image->signature ?? '–' }}</td>
                </tr>
                <tr>
                    <td>Old Nr</td>
                    <td class="oldnr">{{ $image->oldnr ?? '–' }}</td>
                </tr>
            </table>
            <div class="inline-block w-full">
                <h3 class="mb-1 text-xs">Keywords</h3>
                <div class="tags mb-2">
                    @forelse ($image->keywords as $keyword)
                    @if ($keyword->label)
                    <x-links.default href="/?keyword={{ $keyword->id }}" :label="$keyword->label" class="mb-2" />
                    @endif
                    @empty
                    –
                    @endforelse
                </div>
                <h3 class="mb-1 text-xs">Collections</h3>
                <div class="mb-2">
                    @forelse ($image->collections as $collection)
                    @if ($collection->label)
                    <x-links.default :href="route('collections.show', [$collection])" :label="$collection->label"
                        class="mb-2" />
                    @endif
                    @empty
                    –
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
