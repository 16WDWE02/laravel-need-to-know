# Laravel for students

## Create new project

Create a new project with the following instruction:

`composer create-project laravel/laravel --prefer-dist name-of-folder`

Change the `name-of-folder` to whatever you want.

## Routes

If you request a page, view a product or even submit a form, you'll need routes to handle those requests.

In Laravel 5.3 there is a `routes/web.php` file. This is the equivilant of the old `routes.php` from other versions.

### General page requests

Requesting a page by clicking a link is a `GET` request, so you need routes to handle those:

```php
Route::get('/', 'HomeController@index');
Route::get('about', 'AboutController@index');
Route::get('contact', 'ContactController@index');
Route::get('shop', 'ShopController@index');
```

We'll talk about controllers shortly, but just note that we're always calling an `@index` after the name of the controller. This refers to a function inside the controller.

### Sub page requests

Sometimes you want to look at a "sub page", like an individual product from the shop, or a member from the staff page, or an item on a list.

```php
Route::get('shop/{id}', 'ShopController@show');
Route::get('staff/{name}', 'StaffController@show');
```

Notice that it's still a get request, but this time there's two parts to the route `('pagename/id-of-some-kind')` and the function for the controller is now `@show`.

### Form submission request

If you submit a form from a page, such as the login form, registration form, new product form etc you need a `POST` route.

```php
Route::post('login', 'AccountController@login');
Route::post('register', 'AccountController@register');
Route::post('products', 'ShopController@store');
Route::post('blog/post/{id}', 'AccountController@newComment');
```

Form submissions typically go to a `@store` function in a page controller, but you can make up the function name to be whatever you like.

## Controllers

Pages should each have a controller. Controllers control all the features of the page / sub pages. For example the Shop page can show you all products, handle search requests, accept new products, edit existing products and delete products. The Account controller can show you your account details, process edit forms and so on.

Some pages aren't all that functional, like the About page. It should still have a controller though, even if the only thing it does is show you some text.

Create controllers like this: `php artisan make:controller ShopController`

They're pretty empty to start with, but you fill them up with your own functions as you develop the page.

For example the routes file for the home page might look like this:

```php
Route::get('home', 'HomeController@index');
```

We need to make sure there's an `index` function in the HomeController:

```php
<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {

    }
}
```

Here you would get all the data you need (if any) to be displayed on this page and send it to a view (an HTML template). For example:

```php
public function index() {
	$theLatestProducts = [];
	$someSaleItems = [];

	return view('home', compact('theLatestProducts', 'someSaleItems'));
}
```

For now imagine two arrays of data that you want to share with the visitor. We return a home view (which is actually a `home.blade.php` file) and pass it a compacted copy of the two arrays.

In the context of the view file, you simply refer to `$theLatestProducts` and `$someSaleItems` as per normal. This is explained later on.

For the functions that accept data from a form, they look like this:

```php
public function store(Request $request) {
	// Do validation

	// Process data, save it etc

	// Either redirect user or show a view here
}
```

Notice the `Request $request` in the parenthesis of the store function. All the form data is captured there to be used in the function itself. Example of processing form data explained later.

### Form CSRF token

Forms in Laravel are easy. There's just one extra thing every form needs, and that's a `csrf_field()`.

Imagine the following scenario:

Your "friend" knows that you use Facebook, and they give you a link to click. You click the link and suddenly your Facebook account is deleted. Turns out the link you clicked was a "confirm delete account" request to Facebook itself.

Basically you got tricked into submitting a form by clicking a link.

A CSRF token is used by the server to ensure that doesn't happen. Laravel *requires* all forms to have a CSRF token, and the `csrf_field()` function does that. Here is how you use it:

```html
<form action="/login" method="post">
	{{ csrf_field() }}
</form>
```

Easy.

### Processing forms

When you submit a form like the one above, you'll need a `POST` route ready and a `store` function (or other name) in a controller to run. Then, you usually do validation:

```php
public function store(Request $request) {
	// Do validation
	$this->validate($request, [
		
	]);

	// Process data, save it etc

	// Either redirect user or show a view here
}
```

An interesting thing to note about the validate function is that if validation fails, the rest of the PHP is ignored and instead you are redirected back to the form you submitted to see error messages.

Here is an example of a "new product" form that uses validation:

public function store(Request $request) {
	// Do validation
	$this->validate($request, [
		'product_name' => 'required|max:100',
		'product_desc' => 'required|max:1000',
		'product_price'=> 'required|numeric',
		'product_image'=> 'required|image',
		'seller_id'    => 'required|exists:users,id'
	]);

	// Process data, save it etc

	// Either redirect user or show a view here
}

The array keys are the names of the input fields from the form, and the values are the rules. Laravel has a pretty good list of validation rules in the documentation.

If validation fails, as mentioned before, you'll be redirected back to the form itself ready to see error messages. This is what you'd put in your forms to show those messages:

```php
<input type="text" name="product_name">
<?php if($errors->has('product_name')): ?>
<p class="error">{{ $errors->first('product_name') }}</p>
<?php endif ?>
```

It first checks to see if there's an error for `product_name`, and if there is it grabs the first error (because we might have broken multiple rules but we only care about the first one we broke).

