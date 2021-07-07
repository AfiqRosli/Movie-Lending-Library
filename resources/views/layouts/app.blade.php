<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <script defer src="{{ mix('js/app.js') }}"></script>

    <title>Movie Lending Library - @yield('title')</title>
</head>

<body>
    <div id="app" class="container">
        @yield('content')
    </div>
</body>

</html>
