<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

use App\Post;
use App\User;
use App\Tag;
use App\PostTag;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class PostController extends Controller
{

    //get all posts
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();  
        //::select('id' , 'name')->get();

        return \response()->json($posts);
    }


    //get specific post
    public function show($id)
    {
        $post = Post::where('id' , $id)->first();
        //if id not found
        if(!isset($post))
        {
            //post id not found
            $msg = "post not found!";
            return \response()->json($msg);
        }
        //return tags of this post
        $tags = $post->tags()->select('name')->get();
        //response with all post details and it's tags
        $response = ['post' => $post , 'tags' => $tags];
        return \response()->json($response);
    }


    //get user posts
    public function showUserPosts($id)
    {   
        //get posts where user id = id
        $userPosts = Post::where('user_id' , $id)->orderBy('created_at', 'desc')->get(); //desc order 
        return \response()->json($userPosts);
    }

    
    //check post form validation
    public function checkPostValidation(Request $data)
    {
        //validation
        $validator = Validator::make($data->all(), [
            'title' => 'required|min:3|string|max:225',
            'desc' => 'required|string|min:10',
            //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            //'tags' => 'array'
        ]);

        if ($validator->fails()) 
        {
            $msg = $validator->errors()->all();
        }else
        {
            $msg="done";
        }
        return $msg;
    }


    //calculate reading time
    //Depending on an article at medium.com ,the avg of reading time of adult peaple [200 - 225]words per minute
    public function calculateReadingTime($description)
    {
        $words_no = str_word_count($description); //get words number
        $word_per_min = $words_no / 205;          //avg reading time
        $time = round($word_per_min);       //format number of minutes 
        return $time;
    }


    //store record in table of posts in database
    public function storePostRecord(Request $request )
    { 
        $title=$request->title;
        $desc=$request->desc;
        $image=$request->image;

        //get user id
        //$user_id = session('user_id');
        $user_id =5 ;
        
        //estimate reading time
        $time = $this->calculateReadingTime($desc);
        
        //create new post
        $post = new Post;
        //store post
        $post->user_id = $user_id;
        $post->title = $title;
        $post->desc = $desc;
        $post->image = $image;
        $post->reading_time = $time;
        $post->save();
        //::latest()->first()->id;
        
    }


    //store record in table of posts in database
    public function storeTags(Request $request)
    {
        //store tags in database
        $tags=$request->tags;
        //if $tags has data
        if(sizeof($tags)>0)
        {
            //loop on array of tags
            for($i=0 ;$i<sizeof($tags) ;$i++)
            {
                $name = $tags[$i];
                //store new tag
                $tag = new Tag;
                $tag->name = $name;
                $tag->save();
                            
                //store a relation of post and tag in post_tags table
                $post_id = Post::latest()->first()->id;    //get latest post id
                $tag_id = Tag::latest()->first()->id + $i;  //get latest tag id 
                //ask about Class::latest()->first()->id;
                $postTag = new PostTag ;
                $postTag->post_id = $post_id ;
                $postTag->tag_id  = $tag_id ;
                $postTag->save();

            }

        } 
    }
 

    //store post in datebase
    public function storePostWithTags(Request $request)
    {

        //check validation
        $validationMessage = $this->checkPostValidation($request);

        //check validation and store
        if ($validationMessage != 'done') //if validation is failed
        {
            $msg = $validationMessage;
        }
        else //request is valid
        {
            //use database transaction to store post and tags
            DB::transaction(function () use($request) {

                //store data
                $this->storePostRecord($request); //store post
                $this->storeTags($request);     //store tags
                
            });
            
            $msg = $validationMessage;
        }

        return \response()->json($msg);
    }



    //update post
    public function update(Request $request, $id)
    {
        //get post by id
        $post = Post::where('id' , $id)->first();

        //check validation
        $isValidate = $this->checkPostValidation($request);
        
        //check validation and store
        if ($isValidate != 'done')
        {
            $msg = $isValidate ;
        }
        else
        {
            //store post
            $post->title = $request->title;
            $post->desc = $request->desc;
            //$post->image = $image;
            $time = $this->calculateReadingTime($request->desc);
            $post->reading_time = $time;
            $post->save();
            
            $msg = $isValidate;
        }
        return \response()->json($msg);

    }


    //remove post
    public function destroy($id)
    {
        //select post and delete it
        $post = Post::where('id' ,'=', $id)->first();
        $post->delete();

        $msg = "Deleted Successfully";
        return \response()->json($msg);
    }

}
