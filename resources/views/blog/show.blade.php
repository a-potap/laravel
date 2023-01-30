@extends('index')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> {{ $blog->title }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('blog.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <strong>{{ $blog->date }}</strong>
            {{ $blog->text }}
        </div>
    </div>

    <strong>Comments</strong>
    <div>
        @foreach ($blog->comments as $comment)
            <div>
                {{$comment->text}}
            </div>
        @endforeach
    </div>
@endsection
