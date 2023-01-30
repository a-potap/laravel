<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>
    <body class="antialiased">
    <div>
        <a href="/">home</a>
        <a href="/news">news</a>
        <a href="/blog">blog</a>
        <a href="/photo">photo</a>
        <a href="/resume">resume</a>
        <a href="/music">music</a>
        <a href="/video">video</a>
    </div>
        <div>
            @yield('content')
        </div>
    </body>
</html>
