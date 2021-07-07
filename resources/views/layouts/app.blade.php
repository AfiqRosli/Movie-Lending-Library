<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

    @hasSection('style')
    @yield('style')
    @endif

    <title>Movie Lending Library - @yield('title')</title>
</head>

<body>
    <div id="app" class="container">
        @hasSection('h1')
        <h1 class="text-center my-5">@yield('h1')</h1>
        @endif

        @yield('content')
    </div>

    @hasSection('script')
    <script src="{{ mix('js/app.js') }}"></script>

    @yield('script')
    @endif
</body>

</html>
