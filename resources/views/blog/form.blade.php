<h3>{{__('blog.comment_header')}}</h3>

@if ($success = \Illuminate\Support\Facades\Session::get('comment.success'))
    <div class="alert alert-primary" role="alert">
        {{$success}}
    </div>
@endif

<div class="card-body" id="comments">
    <form name="add-post-comment-form"
          id="add-post-comment-form"
          method="post"
          action="{{\App\Helpers\CustomUrl::url('/post/'.$blog->id.'/comment')}}#comments">
        @csrf
        <input type="hidden" name="blog_id" value="{{$blog->id}}">
        <div class="form-group">
            <label for="iduser">{{__('blog.name')}}</label>
            <input type="text" id="iduser" name="iduser" class="form-control" required="" value="{{ old('iduser') }}">
            @error('iduser')
            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="text">{{__('blog.message')}}</label>
            <textarea name="text" class="form-control" required="">{{ old('text') }}</textarea>
            @error('text')
            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="captcha">{{__('blog.captcha')}}</label>
            <div class="row">
                <div class="col-5">
                    <input id="captcha" type="text" class="form-control" placeholder="{{__('blog.captcha_placeholder')}}" name="captcha" required="">
                </div>
                <div class="col-7 captcha">
                    <span>{!! captcha_img() !!}</span>
                    <button type="button" class="btn btn-danger" id="reload" onclick="captchaReload();">
                        ↻
                    </button>
                </div>
            </div>
            @error('captcha')
            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mt-2">
            <button type="submit" class="btn btn-primary">{{__('blog.submit')}}</button>
        </div>

    </form>
</div>

<script type="text/javascript">
    function captchaReload() {
        fetch("/reload-captcha")
            .then((response) => response.json())
            .then((json) => {
                document.querySelector('.captcha span').innerHTML = json.captcha;
            });
    }
</script>
