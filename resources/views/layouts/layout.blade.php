<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Пино</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


    <style>
        body {
            font-family: 'Nunito';
        }
    </style>
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css') }}" type="text/css" media="screen" />
    <link rel="stylesheet" href="" type="text/css" media="screen" />

    <script src="{{ asset('js/app.js') }}?v={{time()}}" defer></script>
    <script src="{{ asset('fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack.js') }}" defer></script>
    <script src="{{ asset('fancybox-1.3.4/fancybox/jquery.easing-1.4.pack.js') }}" defer></script>
</head>
<body class="antialiased">
<div id="app">
    @include('_partials.navbar')
        <div class="col-12">
                @yield('content')
        </div>
</div>
</body>
@yield('script')
</html>
