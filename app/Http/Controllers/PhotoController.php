<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums= Photo::latest()->paginate(5);

        return view('photo.index',compact('albums'));
    }

    public function show($albumId)
    {
        $album = Photo::where('folder' , '=', $albumId)->first();
        return view('photo.show',compact('album'));
    }

}
