<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class AuthController extends Controller
{

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

    	$validator = Validator::make($request->all(), [ 
              'name' => 'required',
              'email' => 'required|email',
              'password' => 'required',  
              'c_password' => 'required|same:password',
              'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);
        }    
		$input = $request->all(); 

		
        
		$user = new User;
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);;
        $user->phone = $input['phone'];
        $user->active = $input['active'];
        if(isset($input['image']))
        {
            $imagedata = file_get_contents($input['image']);
            $base64 = base64_encode($imagedata);
            $imageName = time().'.'.$input['image']->getClientOriginalExtension();
            $input['image']->move(public_path('uploads/users'), $imageName);
            $user->image = $base64 ;
        }
        $user->save();
		$success['token'] =  $user->createToken('AppName')->accessToken;
		return response()->json(['success'=>$success], 200); 
		
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    { 
		if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
		   $user = Auth::user(); 
		   $success['token'] =  $user->createToken('AppName')->accessToken; 
		    return response()->json(['success' => $success], 200); 
		  } else{ 
		   return response()->json(['error'=>'Unauthorised'], 401); 
		   } 
	}
    
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }

    
}
