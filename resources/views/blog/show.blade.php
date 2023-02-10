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

    <div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#commentModal">
            Launch demo modal
        </button>
    </div>

    <div class="comments">
        <strong>{{__('blog.comments')}}:</strong>
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

    <!-- Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
