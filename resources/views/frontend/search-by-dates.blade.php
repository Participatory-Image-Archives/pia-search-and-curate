@extends('base')

@section('content')
<div class="p-4">
    <div class="w-full mb-10">
        <p></p>
    </div>
    <div>
        @csrf
        <div>
            <label for="from" class="inline-block" style="width: 75px">On/From</label>
            <input type="date" name="from" class="border-b" oninput="on_change(this)">
        </div>
        <div class="mb-8">
            <label for="to" class="inline-block" style="width: 75px">To</label>
            <input type="date" name="to" class="border-b" oninput="on_change(this)">
        </div>
        <x-links.cta id="search" href="javascript:;" label="Search with these dates"/>
    </div>
</div>

<script>

    let from = document.querySelector('input[name="from"]'),
        to = document.querySelector('input[name="to"]');

    function on_change(){
        document.querySelector('#search').href = '/?dates='+from.value;

        if(to.value) {
            document.querySelector('#search').href += ','+to.value;
        } else {
            document.querySelector('#search').href += ','+from.value;
        }
    }

</script>
@endsection