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
        @if ($image->dates->count())
            let event_{{ $image->id }} = {
                'title': '{{ $image->title }}',
                'media': {
                    'title': '{{ $image->title }}',
                    'url': 'https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/480,/0/default.jpg',
                    'thumbnail': 'https://pia-iiif.dhlab.unibas.ch/{{$image->base_path}}/{{$image->signature}}.jp2/full/80,/0/default.jpg',
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
            @foreach ($image->dates as $date)
                @if ($date->date)
                    event_{{ $image->id }}.start_date = {
                        'day': '{{ $date->accuracy <= 1 ? date('d', strtotime($date->date)) : '' }}',
                        'month': '{{ $date->accuracy <= 2 ? date('m', strtotime($date->date)) : '' }}',
                        'year': '{{ $date->accuracy <= 3 ? date('Y', strtotime($date->date)) : '' }}'
                    }
                @endif
                @if ($date->end_date)
                    event_{{ $image->id }}.end_date = {
                        'day': '{{ $date->accuracy <= 1 ? date('d', strtotime($date->end_date)) : '' }}',
                        'month': '{{ $date->accuracy <= 2 ? date('m', strtotime($date->end_date)) : '' }}',
                        'year': '{{ $date->accuracy <= 3 ? date('Y', strtotime($date->end_date)) : '' }}'
                    }
                @endif
            @endforeach
            /*
            if(el.date) {
                event_{{ $image->id }}.start_date = format_date(el.date);
            }
            if(el.daterange_0) {
                event_{{ $image->id }}.start_date = format_date(el.daterange_0);
            }
            if(el.daterange_1) {
                event_{{ $image->id }}.end_date = format_date(el.daterange_1);
            }
            */
            timeline_data.events.push(event_{{ $image->id }});
        @endif
    @endforeach

    document.addEventListener("DOMContentLoaded", () => {
        timeline = new TL.Timeline('timeline', timeline_data);
    })
</script>