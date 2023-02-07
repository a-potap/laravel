@php
    $pageTitle = __('common.news');
@endphp

@extends('index')

@section('content')
    <h1>{{$pageTitle}}</h1>
    <div class="row">
        <div class="col-12 text-end">
            <a class="btn btn-primary" href="{{ url()->previous() }}"> Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <p>
                <i>{{ \Carbon\Carbon::parse($news->date)->format('d/m/Y') }}</i><br>
                {!! $news->getLocalizedText() !!}
            </p>
        </div>
    </div>
@endsection
