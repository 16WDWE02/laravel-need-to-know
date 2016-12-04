@extends('master')

@section('content')
<h1>Home page</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum commodi non, laudantium quia est, recusandae debitis mollitia adipisci, ea, sapiente expedita. Dignissimos cumque quisquam, voluptate, sit obcaecati sed! Ut, fugit.</p>


<?php foreach($allPosts as $post): ?>
<article>
	<h1><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h1>
	<p>{{ $post->excerpt }}</p>
</article>
<?php endforeach ?>

@endsection