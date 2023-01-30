@extends('index')

@section('content')
    @foreach ($albums as $album)
        <div>{{ $album->name }}</div>
    @endforeach
@endsection
