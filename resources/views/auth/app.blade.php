<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <title>{{$title ?? env('APP_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="/css/app.css?v={{env('ASSETS')}}" />

    @stack('styles')

  </head>
  <body>

    @yield('content')

    <script src="{{ asset('js/app.js') }}?v={{env('ASSETS')}}"></script>
  </body>
</html>
