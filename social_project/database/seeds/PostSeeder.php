<?php

use App\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i=2 ; $i<5 ;$i++)
        {
            $post = new Post ;
            $post->title = "post number $i";
            $post->user_id = $i;
            $post->desc  = "desc of post $i description of the first description of the first post after deploying description of the first post after deploying";
            $post->image = "image$i.png";
            $post->reading_time = 3+$i;
            $post->save();
        }
    }
}
