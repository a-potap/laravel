@php
    $pageTitle = __('common.photo');
@endphp

@extends('index')

@section('content')
    <h1>{{$pageTitle}}</h1>
    @foreach ($albums as $album)
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <a href="{{url("/photo/{$album->folder}")}}" class="album_face" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.0)), url('/albums/foto/{{$album->folder}}/fase.JPG')">
                    <h3>{{ $album->getLocalizedName() }}</h3>
                </a>
            </div>
        </div>
    @endforeach
@endsection
