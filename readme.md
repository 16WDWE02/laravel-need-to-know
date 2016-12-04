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

Forms in Laravel are typical HTML forms with inputs and name attributes. There's just one extra thing every form needs, and that's a `csrf_field()`.

Imagine the following scenario:

Your "friend" knows that you use Facebook, and they give you a link to click. You click the link and suddenly your Facebook account is deleted. Turns out the link you clicked was a "confirm delete account" request to Facebook itself.

Basically you got tricked into submitting a form by clicking a link.

A CSRF token is used by the server to ensure that doesn't happen. Laravel *requires* all forms to have a CSRF token, and the `csrf_field()` function does that. Here is how you use it:

```html
<form action="/login" method="post">
	{{ csrf_field() }}
</form>
```

Now when anyone sends a form that Laravel was not expecting from that user, it will be rejected.

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

An interesting thing to note about the validate function is that if validation fails, the rest of the PHP is ignored in that function and instead you are redirected back to the page with the form on it to see error messages.

Here is an example of a "new product" form that uses validation:

```php
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
```

The array keys are the names of the input fields from the form, and the values are the rules. Laravel has a pretty good list of validation rules in the documentation.

If validation fails, as mentioned before, you'll be redirected back to the form itself ready to see error messages. This is what you'd put in your forms (after each input field) to show those messages:

```php
<input type="text" name="product_name">
<?php if($errors->has('product_name')): ?>
<p class="error">{{ $errors->first('product_name') }}</p>
<?php endif ?>
```

It first checks to see if there's an error for `product_name`, and if there is it grabs the first error (because we might have broken multiple rules but we only care about the first one we broke).

Successful form validation runs the remaining code in the store function. This is where you would start putting data into the database with models, explained later.

## Databases

### Migrations

Migrations in Laravel are instructions for how a database should be built, and since it's written in a PHP file you can track it with git. Otherwise you'd have to carry around .sql dumps of your database that could get lost or changed.

Migrations are run by date, and each migration filename starts with a date depending on when it was created. Laravel takes this into account when running them. It's important to *avoid modifying migration files that have already run*. Create a new migration to modify any existing tables or columns as needed. If you change them after they've been run, you'll probably break the ability to undo and re-run them.

Creating a table would use the following terminal command: `php artisan make:migration create_posts_table --create=posts`

This makes a file that looks like the following:

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
```

The `up()` function is for when you run a migration. The `down()` function is for when you undo a migration.

The Laravel Documentation contains everything about migrations and how to use them. The only thing you'll want to consider is the logical order of table creation. For example if users can create blog posts, you'd have to ensure the users table exists before the posts table, because the posts table would use a user_id foreign key.

Updating an existing table would use the following command: `php artisan make:migration add_profileimage_to_users_table --table=users`

The two different commands affect how the migration works. See if you can spot the difference between a create migration and one that changes an existing table.

### Seeds

Seeding a table is used for dummy data. It's useful during development of a site so you don't have to keep recreating data every time you rollback and re-run a migration. Live websites with real data should simply use .sql dumps to ensure minimal loss of data if a migration has to be re-run for whatever reason. As mentioned earlier, you shouldn't re-run / edit migrations that have already run. Try creating new migrations to adjust existing tables and columns, and you won't lose data that way. 

### Models (Eloquent)

Each table in your database should have Model file that helps you interact with that table to do tasks like CRUD. When you create a model it actually gives you an almost bare file, but Models are based on another class that has pre-built common functionality. It's only when you have specific instructions to run on a table that you would write anything in the Model file.

Create a model for a table using the following command: `php artisan make:model Posts`

Try to make the model name the same as the table name as Laravel will assume the table name based on the file name unless told otherwise.

#### Reading data from table and sending to a view

You'll need to add the namespace for the model in the controller that uses it. `use App\Posts;`. Without it the controller won't know what you're talking about.

```php
<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Posts;

class HomeController extends Controller
{
    public function index() {
		
		$allPosts = Posts::all();

		return view('home', compact('allPosts'));

    }
}
```

The code above would grab all data from the posts table and send it to the `home.blade.php` template to be rendered via PHP (or the blade templating system):

```php
<h1>All Products</h1>

<?php foreach($allPosts as $post): ?>
<article>
	<h1>{{ $post->title }}</h1>
	<small>Written on: {{ $post->created_at }}</small>
	<p>{{ $post->content }}</p>
</article>
<?php endforeach; ?>
```