@php
    $pageTitle = __('common.blog');
@endphp

@extends('index')

@section('content')
    <h1>{{$pageTitle}}</h1>
    <div class="row">
        <div class="col-10">
            <h2> {{ $blog->getLocalizedTitle() }}</h2>
        </div>
        <div class="col-2 text-end">
            <a class="btn btn-primary" href="{{\App\Helpers\CustomUrl::url('/blog')}}"> Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <strong>{{ $blog->date }}</strong> <br>
            {!! $blog->getLocalizedText() !!}
        </div>
    </div>

    @if (count($blog->comments))
        <div class="comments">
            <h3>{{__('blog.comments')}}:</h3>
            @foreach ($blog->comments as $comment)
                <div class="row">
                    <div class="col-11 offset-1">
                        <strong>Гость</strong>
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
