@php
    $pageTitle = 'News';
@endphp

@extends('index')

@section('content')
    <h1>{{$pageTitle}}</h1>
    @foreach ($news as $item)
        <div class="row">
            <div class="col-12">
                <a href="{{url('news', $item->id)}}">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</a>
                <p>
                    {!! $item->text !!}
                </p>
            </div>

        </div>
    @endforeach

    {!! $news->links() !!}

@endsection
