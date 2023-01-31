<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index() {
        $news = News::latest()->paginate(5);

        return view('welcome',compact('news'));
    }
}
