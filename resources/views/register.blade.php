@extends('master')

@section('content')

<h1>Register new account</h1>

<form action="/register" method="post">
	{{ csrf_field() }}

	<div>
		<label for="name">Name: </label>
		<input type="text" name="name" id="name" placeholder="Ben Abbott" value="{{ old('name') }}">
		<?php if($errors->has('name')): ?>
		<small>{{ $errors->first('name') }}</small>
		<?php endif ?>
	</div>

	<div>
		<label for="email">Email: </label>
		<input type="text" name="email" id="email" placeholder="you@website.com" value="{{ old('email') }}">
		<?php if($errors->has('email')): ?>
		<small>{{ $errors->first('email') }}</small>
		<?php endif ?>
	</div>

	<div>
		<label for="password">Password: </label>
		<input type="password" name="password" id="password">
	</div>

	<div>
		<label for="password2">Confirm password: </label>
		<input type="password" name="password_confirmation" id="password2">
		<?php if($errors->has('password')): ?>
		<small>{{ $errors->first('password') }}</small>
		<?php endif ?>
	</div>

	<input type="submit" name="register">

</form>

@endsection