<link title="timeline-styles" rel="stylesheet" 
    href="https://cdn.knightlab.com/libs/timeline3/latest/css/timeline.css">
<script src="https://cdn.knightlab.com/libs/timeline3/latest/js/timeline.js"></script>

<div id="timeline" class="w-full min-h-screen"></div>

<script type="text/javascript">

    let timeline_data = {
        'events': [],
        'height': '100%'
    }

    @foreach ($collection->images as $image)
        @if ($image->date)
            let event_{{ $image->id }} = {
                'title': '{{ $image->title }}',
                'media': {
                    'title': '{{ $image->title }}',
                    'url': 'https://sipi.participatory-archives.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/480,/0/default.jpg',
                    'thumbnail': 'https://sipi.participatory-archives.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/80,/0/default.jpg',
                    'link': '{{ route('images.show', [$image]) }}',
                    'link_target': '_blank'
                },
                'text': {
                    'headline': '{{ $image->title }}',
                    'text': `
                        @foreach ($image->comments as $comment)
                            @if ($comment->comment)
                                – {{ $comment->comment }}<br>
                            @endif
                        @endforeach
                    `
                }
            }
            @if ($image->date->date)
                event_{{ $image->id }}.start_date = {
                    'day': '{{ $image->date->accuracy <= 1 ? date('d', strtotime($image->date->date)) : '' }}',
                    'month': '{{ $image->date->accuracy <= 2 ? date('m', strtotime($image->date->date)) : '' }}',
                    'year': '{{ $image->date->accuracy <= 3 ? date('Y', strtotime($image->date->date)) : '' }}'
                }
            @endif
            @if ($image->date->end_date)
                event_{{ $image->id }}.end_date = {
                    'day': '{{ $image->date->accuracy <= 1 ? date('d', strtotime($image->date->end_date)) : '' }}',
                    'month': '{{ $image->date->accuracy <= 2 ? date('m', strtotime($image->date->end_date)) : '' }}',
                    'year': '{{ $image->date->accuracy <= 3 ? date('Y', strtotime($image->date->end_date)) : '' }}'
                }
            @endif
            timeline_data.events.push(event_{{ $image->id }});
        @endif
    @endforeach

    document.addEventListener("DOMContentLoaded", () => {
        timeline = new TL.Timeline('timeline', timeline_data);
    })
</script>