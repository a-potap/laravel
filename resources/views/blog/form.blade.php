<h3>Оставить комментарий</h3>

@if ($success = \Illuminate\Support\Facades\Session::get('comment.success'))
    <div class="alert alert-primary" role="alert">
        {{$success}}
    </div>
@endif

<div class="card-body">
    <form name="add-post-comment-form" id="add-post-comment-form" method="post" action="{{url('/post/'.$blog->id.'/comment')}}">
        @csrf
        <input type="hidden" name="blog_id" value="{{$blog->id}}">
        <div class="form-group">
            <label for="iduser">Name</label>
            <input type="text" id="iduser" name="iduser" class="form-control" required="">
        </div>
        <div class="form-group">
            <label for="text">Comment</label>
            <textarea name="text" class="form-control" required=""></textarea>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>

    </form>
</div>

<div >
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
