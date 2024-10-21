@php
    $pageTitle = __('welcome.header');
@endphp

@extends('index')

@section('content')

    <div class="starter text-end">
        <h1>{{__('welcome.header')}}</h1>

        <p class="lead">
            {{__('welcome.header_description')}}
        </p>

        <p>
            <br>
            <br>
            <a href="">{{__('welcome.motto')}}</a>
    </div>

    <div class="row">
        <div class="col-sm-6">
            @if (App::isLocale('en'))
                @include('welcome.en')
            @else
                @include('welcome.ru')
            @endif
        </div>

        <div class="col-sm-6">
            <h2>{{__('common.news')}}</h2>

            <div class="news">
                @foreach ($news as $item)
                    <div class="row">
                        <div class="col-12">
                            <i>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</i>
                            <p>
                                {!! $item->getLocalizedText() !!}
                            </p>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
