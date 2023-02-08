<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\BlogCollection;
use App\Http\Resources\Api\BlogResource;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        return new BlogCollection(Blog::paginate(10));
    }

    public function show($id)
    {
        return (new BlogResource(Blog::findOrFail($id)))->withExtra();
    }
}
