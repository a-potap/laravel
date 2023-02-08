<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\PhotoCollection;
use App\Http\Resources\Api\PhotoResource;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotosController extends Controller
{
    public function index()
    {
        return new PhotoCollection(Photo::paginate(10));
    }

    public function show($id)
    {
        return (new PhotoResource(Photo::findOrFail($id)))->withExtra();
    }
}
