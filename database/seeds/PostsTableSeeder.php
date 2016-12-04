<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate random posts
        for($i=0; $i<100; $i++) {
        	DB::table('posts')->insert([
	            'title' => str_random(40),
	            'content' => str_random(1000),
	            'excerpt' => str_random(100),
	            'user_id' => 1
	        ]);
        }
    }
}
