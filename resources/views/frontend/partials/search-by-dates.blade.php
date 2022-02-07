<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIA Search and Curate</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    
    @yield('styles')

</head>

<body>
        <div>
            <label for="from" class="inline-block" style="width: 100px">On/From</label>
            <input type="date" name="from" class="border-b" oninput="on_change(this)">
        </div>
        <div class="mb-8">
            <label for="to" class="inline-block" style="width: 100px">To</label>
            <input type="date" name="to" class="border-b" oninput="on_change(this)">
        </div>
        <x-links.cta id="search" href="javascript:;" label="Search with these dates" target="_top"/>
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

</body>
</html>