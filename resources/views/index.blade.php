<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Simple CMS"/>
    <meta name="author" content="Sheikh Heera"/>


    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


</head>
<body class="antialiased">
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar w/ text</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                </ul>
                <span class="navbar-text">
        Navbar text with an inline element
      </span>
            </div>
        </div>
    </nav>
    <div class="row header">
        <div class="col-sm-4 hidden-xs">
            <a href="/"> <img src="/img/logo.jpg"></a>
        </div>
        <div class="col-sm-8 menu">
            <div class="language hidden-xs">
                <a class="active" href="/ru/post/48">RU</a>
                <a class="" href="/en/post/48">EN</a>
            </div>
            <div class="row">
                <div>
                    <a href="/">home</a>
                    <a href="/news">news</a>
                    <a href="/blog">blog</a>
                    <a href="/photo">photo</a>
                    <a href="/resume">resume</a>
                    <a href="/music">music</a>
                    <a href="/video">video</a>
                </div>
            </div>
        </div>
    </div>

    <div>
        @yield('content')
    </div>
</div>

</body>
</html>
