<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
//to allaw requests
Route::group(['middleware' => ['cors']], function () {

    //check access key
    Route::group(['middleware' => ['Authkey']], function () {

*/

        //get all users
        Route::get('users', 'UserController@index');
        //get specific user
        Route::get('users/{id}', 'UserController@show');

        //create new user
        Route::post('users/register', 'UserController@register');
        //login user
        Route::post('users/login', 'UserController@validationAndLogin');
        //update user
        Route::post('users/update/{id}', 'UserController@update');

        //get all posts
        Route::get('posts', 'PostController@index');
        //get specific post
        Route::get('posts/{id}', 'PostController@show');

        //get user posts
        Route::get('posts/userPosts/{id}' , 'PostController@showUserPosts');
        //create new Post
        Route::post('posts/store', 'PostController@storePostWithTags');
        //update Post
        Route::post('posts/update/{id}', 'PostController@update');
        //delete Post
        Route::post('posts/delete/{id}', 'PostController@destroy');

        //search by key
        Route::get('posts/search/{key}' , 'PostTagController@index');
/*         
    });
    
});


