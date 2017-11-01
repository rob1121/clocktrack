<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://bootswatch.com/spacelab/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    @stack('style')
</head>
<body style="overflow-y: scroll">
    @include('layouts.nav')
    <div id="wrapper"  hidden>
        @yield('content')
        <footer>
            <hr/>
            <p class="text-center">All Rights Reserved. &copy; <?= date('Y'); ?></p>
        </footer>
    </div>
    <!-- Scripts -->
    <script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
    <!-- Scripts -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    @stack('script')
    <script>
        $('document').ready(function() {
            $('#wrapper').fadeIn();
        })
    </script>
</body>
</html>
