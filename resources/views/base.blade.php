<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIA Search and Curate</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/node_modules/slim-select/dist/slimselect.min.css">
    <link rel="stylesheet" href="/css/app.css">
    
    @yield('styles')

</head>
<body class="min-h-screen">

    @yield('content')
    
    <div class="fixed bottom-4 left-4">
        @include('partials.nav')
    </div>

    <!--<script defer src="https://unpkg.com/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>-->
    <!--<script defer src="https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>-->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="/node_modules/slim-select/dist/slimselect.min.js"></script>

    @yield('scripts')

</body>
</html>