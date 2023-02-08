<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\CommentCollection;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index(int $blog_id)
    {
        return new CommentCollection(Comment::where('blog_id', $blog_id)->orderBy('date')->paginate(10));
    }
}
