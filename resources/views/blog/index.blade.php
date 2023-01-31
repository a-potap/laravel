@php
    $pageTitle = 'Blog';
@endphp

@extends('index')

@section('content')
    <h1>{{$pageTitle}}</h1>
    @foreach ($blog as $item)
        <div class="post-item">
            <h2><a href="{{url("/post/{$item->id}")}}"> {{ $item->title }}</a></h2>
            <div>
                <p>
                    <i>{{ $item->date }}</i>
                </p>
            </div>

            <div>
                {{ mb_substr($item->text, 0, 200) }}
            </div>
        </div>
    @endforeach

    {{ $blog->links() }}

@endsection
