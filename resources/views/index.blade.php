<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Simple CMS"/>
    <meta name="author" content="Sheikh Heera"/>

    <link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

    <title>{{ config('app.name', 'Laravel') }} - {{$pageTitle ?? ''}}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


</head>
<body class="antialiased">
<div class="container">
    <div class="row header">
        <div class="col-sm-4 d-none d-sm-block">
            <a href="{{\App\Helpers\CustomUrl::url('/')}}"> <img src="/img/logo.jpg"></a>
        </div>
        <div class="col-sm-8">
            <div class="language d-none d-sm-block text-end">
                <a class="active" href="{{\App\Helpers\CustomUrl::getLocalizedCurrentPage('ru')}}">RU</a>
                <a class="" href="{{\App\Helpers\CustomUrl::getLocalizedCurrentPage('en')}}">EN</a>
            </div>
            <div class="row">
                <nav class="navbar navbar-expand-sm navbar-dark">
                    <div class="container-fluid">
                        <a class="navbar-brand d-block d-sm-none" href="#">POTAPOV</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{\App\Helpers\CustomUrl::url('/news')}}">{{__('common.news')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{\App\Helpers\CustomUrl::url('/blog')}}">{{__('common.blog')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{\App\Helpers\CustomUrl::url('/photo')}}">{{__('common.photo')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{\App\Helpers\CustomUrl::url('/music')}}">{{__('common.music')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{\App\Helpers\CustomUrl::url('/video')}}">{{__('common.video')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{\App\Helpers\CustomUrl::url('/resume')}}">{{__('common.resume')}}</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div>
        @yield('content')
    </div>
</div>
<footer class="footer">
    <div class="container text-end">
        <p>&copy; a-potap <?= date('Y') ?></p>
    </div>
</footer>
</body>
</html>
