<?php

use Illuminate\Database\Seeder;
use App\PostTag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i=2 ; $i<5 ;$i++)
        {
            $post_tags = new PostTag ;
            $post_tags->tag_id = 4;
            $post_tags->post_id = $i;
            
            $post_tags->save();
        }
    
    }
}
