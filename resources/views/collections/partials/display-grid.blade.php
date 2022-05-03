<div id="searchable-list">
    <ul class="list grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 grid-flow-row print-grid print-w-full">
        @foreach ($images as $image)
        <li>
            @if($collection ?? false)
            <a href="{{ route('images.show', [$image]) }}?cid={{ $collection->id }}&iid={{ $image->id }}" class="print-image">
            @else
                <a href="{{ route('images.show', [$image]) }}" class="print-image">
            @endif
                <img class="inline-block mr-2 w-full"
                    src="https://sipi.participatory-archives.ch/{{$image->base_path != '' ? $image->base_path.'/' : ''}}{{$image->signature}}.jp2/full/360,/0/default.jpg"
                    alt="{{$image->title}}" title="{{$image->title}}">
                <div class="print-image-meta p-2">
                    <span class="title text-xs">{{ $image->title }}</span>
                </div>
                <div class="hidden">
                    <span class="signature">{{ $image->signature ?? '–' }}</span>
                    <span class="oldnr">{{ $image->oldnr ?? '–' }}</span>
                    <span class="tags">
                        @foreach ($image->keywords as $keyword)
                        @if ($keyword->label)
                        {{ $keyword->label }}
                        @endif
                        @endforeach
                    </span>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
</div>
