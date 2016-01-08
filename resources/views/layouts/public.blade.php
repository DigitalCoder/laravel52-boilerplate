<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Village Capital {{ isset($title) ? ' - '.$title : '' }}</title>
        <link rel="stylesheet" href="{{ cdn(elixir('css/vendor.css')) }}">
        <link rel="stylesheet" href="{{ cdn(elixir('css/app.css')) }}">
        @include('layouts.partials.favicons')

        @yield('header')
    </head>
    <body class="public-page route-{{Route::currentRouteName()}}">
        <div id="header">
        </div>

        <div class="page-content">
            @yield('content')
        </div>
        @include('layouts.partials.google-analytics')

        <script src="{{ cdn(elixir('js/vendor.js')) }}"></script>
        <script src="{{ cdn(elixir('js/app.js')) }}"></script>
        <script type="text/javascript">var csrfToken="{!! csrf_token() !!}";</script>
        @yield('script')
    </body>
</html>
