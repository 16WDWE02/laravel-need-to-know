<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Posts;

class PostsController extends Controller
{
    public function show($id) {

    	$post = Posts::find($id);

    	return view('post', compact('post'));

    }
}
