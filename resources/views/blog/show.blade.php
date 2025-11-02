@php
    $pageTitle = __('common.blog');
@endphp

@extends('index')

@section('content')
    <h1>{{$pageTitle}}</h1>
    <div class="row">
        <div class="col-12">
            <h2> {{ $blog->getLocalizedTitle() }}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <span class="post_date mb-3">{{ $blog->date }}</span>
            {!! $blog->getLocalizedText() !!}
        </div>
    </div>

    @if (count($blog->comments))
        <div class="comments">
            <h3>{{__('blog.comments')}}:</h3>
            @foreach ($blog->comments as $comment)
                <div class="row">
                    <div class="col-11 offset-1">
                        <strong>{{$comment->iduser}}</strong>
                        <span>{{ \Carbon\Carbon::parse($comment->date)->format('d/m/Y') }}</span>
                        <p>
                            {{$comment->text}}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @include('blog.form')
@endsection
