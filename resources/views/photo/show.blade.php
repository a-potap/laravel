@php
    $pageTitle = __('common.photo');
@endphp

@extends('index')

@section('content')
    <h1>{{$pageTitle}}</h1>

    <h2>{{ $album->getLocalizedName() }}</h2>
    <div class="row">
        <div class="col-12">
            <p>
                <i>{{ \Carbon\Carbon::parse($album->date_create)->format('d/m/Y') }}</i>
            </p>

            <p>
                {{ $album->getLocalizedDescription() }}
            </p>
        </div>
    </div>
    <div class="row">
        @foreach ($album->getFiles() as $file)
            <div class="col-sm-3">
                <a href="{{$file['path']}}" data-lightbox="image-1" data-title="{{$album->name}}">
                    <img src="{{$file['path']}}" class="img-fluid photo">
                </a>
            </div>
        @endforeach
    </div>
@endsection
