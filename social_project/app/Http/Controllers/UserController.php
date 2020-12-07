<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class UserController extends Controller
{

    //get all users
    public function index()
    {
        //return response()->json(Auth::user());
        $users = User::orderBy('created_at', 'desc')->get();  
        //::select('id' , 'name')->get();

        return \response()->json($users);
    }


    //get specific user
    public function show($id)
    {
        $user = User::where('id' , $id)->first();
        return \response()->json($user);
    }
   

    //check Register form validation
    public function checkRegisterValidation(Request $data)
    {
        //validation
        $validator = Validator::make($data->all(), [
            'name' => 'required|min:3|string|max:100',
            'email' => 'required|email|max:150',
            'password' =>'required|min:6|max:150|string',
            'work' =>'required|min:1|max:150|string',
            'location' =>'required|min:3|max:150|string',
            //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048' 
        ]);

        if ($validator->fails()) {
            //get array of error message
            $msg = $validator->errors()->all();
        }else
        {
            $msg="done";
        }
        return $msg;
    }


    //store new user
    public function register(Request $request)
    {
        $isValidate = $this->checkRegisterValidation($request);

        //check validation and store
        if ($isValidate != 'done')
        {
            $msg = $isValidate;
        }
        else
        {
            //store
            $name = $request->name;
            $email=$request->email;
            $password = $request->password;
            $work = $request->work;
            $location =$request->location;
            $image = $request->image;

            //generate api token
            $token = $this->generateToken();
            
            $user = new User ;
            $user->name = $name;
            $user->email = $email;
            $user->password = \Hash::make($password);
            $user->work = $work;
            $user->location = $location;
            $user->image = $image;
            $user->api_token = $token;
            $user->save();

            $msg = $isValidate;

        }

        //$data = ['user' => $user ,'message'=>$msg ];
        return \response()->json($msg);

    }


    //generate api token
    public function generateToken()
    {
        $token = str::random(90).\uniqid();
        return $token;
    }


    //check validation and login user
    public function validationAndLogin(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:150',
            'password' =>'required|min:6|max:150|string'
        ]);

        if ($validator->fails()) {
            //get array of error message
            $msg = $validator->errors()->all();
            return \response()->json(['message' =>$msg]);
        }else
        {
            //login for a valid date
            $login = $this->login($request);
            return \response()->json($login);
        }

    }

    public function storeUserInSession(User $user)  //store user data in session
    {
        session(['user_id' => $user->id,
        'user_name' =>$user->name,
        'user_email' =>$user->email,
        'user_image' =>$user->image ,
        'user_token' =>$user->api_token]);
    }

    
    //login function
    public function login(Request $request)
    {
        
        $email = $request->email;
        $password = $request->password;
        
        $credentials = $request->only('email', 'password');
        
        //if user is exist response with access token of user
        if (Auth::attempt($credentials)) {
           $user = Auth::user();
           $token = $user->api_token;  //get access token
           $this->storeUserInSession($user); //store user in session
           $msg = "logged in successfuly ";
           $response = ['token' => $token , 'message' => $msg]; //response with access token

           return \response()->json($response);
        }
        else{
            //if login failed
            $msg="failed to login!";
            return \response()->json($msg);
        }

    }


    //check Update form validation
    public function checkUpdateValidation(Request $data)
    {
        //validation
        $validator = Validator::make($data->all(), [
            'name' => 'required|min:3|string|max:100',
            'work' =>'required|min:1|max:150|string',
            'location' =>'required|min:3|max:150|string',
            //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048' 
        ]);

        if ($validator->fails()) {

            $msg = $validator->errors()->all();
        }else
        {
            $msg="done";
        }
        return $msg;
    }


    //update user profile
    public function update(Request $request, $id)
    {
        $user = User::where('id' , $id)->first();
        
        //check validation and update
        $isValidate = $this->checkUpdateValidation($request);

        if ($isValidate != 'done')
        {
            $msg = $isValidate;
        }
        else
        {
            $name = $request->name;
            $work = $request->work;
            $location =$request->location;
            $image = $request->image;
            
            
            $user->name = $name;
            $user->work = $work;
            $user->location = $location;
            $user->image = $image;
            $user->save();

            $msg = $isValidate;
        }

        $data = [

            'user' => $user ,
            'message'=>$msg
        ];

        return \response()->json($msg);

    }


}
