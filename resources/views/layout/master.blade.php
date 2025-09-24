<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="ISCI">
        <meta name="author" content="Sebastián Aguilera <sebastian@procodigo.cl>">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <title>@yield('title_1', config('app.name')) - @yield('title_2', '')</title>

        @yield('pre_css')

        @vite('resources/js/bootstrap-app.js')
        @vite('resources/less/app.less')
        @vite('resources/css/payment.css')

        @yield('css')
    </head>
    <body class="@yield('class-body')">

        @include('layout.menu')

        <main class="@yield('class-main')">
            @yield('content')
        </main>

        @include('layout.footer')

        @yield('pre_js')
        @yield('js')
    </body>
</html>
