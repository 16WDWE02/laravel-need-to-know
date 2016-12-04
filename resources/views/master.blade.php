<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	
	<nav>
		<ul>
			<li><a href="/">Home</a></li>
			<li><a href="/about">About</a></li>
			<li><a href="/contact">Contact</a></li>
			<li><a href="/register">Register</a></li>
		</ul>
	</nav>

	<main>
		@yield('content')
	</main>

	<footer>
		<p>Copyright &copy; Your Name</p>
	</footer>

</body>
</html>