<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <title>{{$title ?? env('APP_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="client-id" content="{{$client_id ?? 'null'}}">
    <meta name="client-token" content="{{$client_token ?? 'null'}}">

    <link rel="stylesheet" type="text/css" href="/css/app.css?v={{env('ASSETS')}}" />

    @stack('styles')

  </head>
  <body>

    <div id="app">
      <h1 class="text-center">Медленный интернет</h1>
      <h2 class="text-center">Перезагрузите страницу</h2>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="{{ asset('js/cabinet.js') }}?v={{env('ASSETS')}}"></script>
  </body>
</html>
