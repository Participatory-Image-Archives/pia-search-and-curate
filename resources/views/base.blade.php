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
    
    @livewireStyles
    @yield('styles')

</head>
<body class="min-h-screen">

    @yield('content')
    
    <div class="fixed bottom-4 left-4">
        @include('partials.nav')
    </div>

    <script src="/node_modules/slim-select/dist/slimselect.min.js"></script>

    @livewireScripts
    @yield('scripts')

</body>
</html>
