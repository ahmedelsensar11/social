<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    
    public function index($key)
    {
        //get posts by search with hashtag
        $tag = Tag::where('name' , '=' , $key)->first();  //handle it if return null
        $posts = $tag->posts()->orderBy('created_at', 'desc')->get();

        return \response()->json($posts);
    }

    
}
