<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Posts;

class HomeController extends Controller
{
    // Standard show the page to the visitor function
    public function index() {

    	$allPosts = Posts::all();

    	return view('home', compact('allPosts'));

    }
}
