<?php

namespace App\Http\Controllers;

use App\Helpers\CustomUrl;
use App\Http\Requests\StoreCommentRequest;
use App\Jobs\ValidateComment;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Support\Facades\Redirect;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blog = Blog::latest()->paginate(5);

        return view('blog.index',compact('blog'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return view('blog.show',compact('blog'));
    }

    public function comment(StoreCommentRequest $request)
    {
        // $comment = Comment::create($request->all());

        ValidateComment::dispatch($request->all());

        return Redirect::to( CustomUrl::url('/post/'.$request->post('blog_id')))->with('comment.success', __('blog.success'));
    }
}
