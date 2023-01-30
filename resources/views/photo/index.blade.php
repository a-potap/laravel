@extends('index')

@section('content')
    @foreach ($albums as $album)
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <a href="{{url("/photo/{$album->folder}")}}" class="album_face" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.0)), url('/albums/foto/{{$album->folder}}/fase.JPG')">
                    <h3>{{ $album->name }}</h3>
                </a>
            </div>
        </div>
    @endforeach
@endsection
