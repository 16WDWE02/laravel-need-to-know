@extends('master')

@section('content')

<h1>{{ $post->title }}</h1>
<small>Written by: {{ $post->user->name }}</small>
<p>{{ $post->content }}</p>

@endsection