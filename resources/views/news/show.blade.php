@php
    $pageTitle = 'News';
@endphp

@extends('index')

@section('content')
    <h1>{{$pageTitle}}</h1>
    <div class="row">
        <div class="col-12 text-end">
            <a class="btn btn-primary" href="{{ route('news.index') }}"> Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {!! $news->text !!}
        </div>
    </div>
@endsection
