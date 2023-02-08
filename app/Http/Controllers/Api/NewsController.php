<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\NewsCollection;
use App\Http\Resources\Api\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        return new NewsCollection(News::paginate(10));
    }

    public function show($id)
    {
        return new NewsResource(News::findOrFail($id));
    }
}
