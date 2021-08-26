<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>playground</title>

    <link rel="stylesheet" href="node_modules/bulma/css/bulma.min.css">
    
    @yield('styles')

</head>
<body>

    @yield('content')

    <!--<script src="node_modules/jszip/dist/jszip.min.js"></script>
    <script src="node_modules/file-saver/dist/FileSaver.min.js"></script>-->
    @yield('scripts')

</body>
</html>