<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index() {

    	return view('register');

    }

    public function store(Request $request) {

    	// Validation
    	$this->validate($request, [
    		'name' => 'required',
    		'email' => 'required|email',
    		'password' => 'required|min:8|confirmed'
    	]);

    	// Hash the password
    	

    	// Log them in

    	// Redirect to an account page


    }
}
