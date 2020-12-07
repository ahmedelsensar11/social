<?php

namespace App;
use App\Tag;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{   
    //tags of specific post
    public function tags()
    {
        return $this->belongsToMany('App\Tag' ,'post_tags');
    }

}
