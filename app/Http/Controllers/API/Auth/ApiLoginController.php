<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiLoginController extends Controller
{
    use AuthenticatesUsers;

    public function login (Request $request) {

        //Validate ng Request
        // $validationRule = [
        //     'email'=>"required|string",
        //     'password'=> "required|string",
        //     'role' => "required|string"
        // ];

        
        // $validation = Validator::make($request->all(), $validationRule);
        
        // if($validation->fails()){
        //     return response()->json([
        //         'status' => false,
        //         'message' => $validation->errors()->first(),
                
        //     ]);
        // }
        

        // //Check kung nag match
        // $credentials = request(['email', 'password', 'role']);
        // if(!Auth::attempt($credentials)){
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Incorrect login details',
        //     ]);
        // }


        // //Return data and Bearer Token
        // $user = $request->user();
        // $bearerToken = $user->createToken('auth_token')->plainTextToken;


        // return response()->json([
        //     "status" => true,
        //     "message" => "Succesfully Login",
        //     "user" => $user,
        //     "token" => $bearerToken
        // ]);
        $validationRule = [
            'email'=>"required|email",
            'password' => "required|string",
            'role' => "required|string|in:student,guardian"
        ];
        $validation = Validator::make($request->all(), $validationRule);
        if ($validation->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validation->errors()->first(),
            ]);
        }

        // Validate email and password
        $credentials = $request->only('email', 'password');
        
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Incorrect login details',
            ], 401);
        }

        

        // Get the authenticated user
        $user = Auth::user();
        
        // Generate token (assuming you use Laravel Sanctum)
        $bearerToken = $user->createToken('auth_token')->plainTextToken;

        // Based on the role, redirect to the appropriate route/page
        if ($user->role === 'student') {
            // Redirect to student dashboard
            return response()->json([
                'status' => true,
                'message' => 'Logged in as Student',
                'role' => 'student',
                'user' => $user,
                'token' => $bearerToken
            ]);
        } elseif ($user->role === 'guardian') {
            // Redirect to guardian dashboard
            return response()->json([
                'status' => true,
                'message' => 'Logged in as Guardian',
                'role' => 'guardian',
                'user' => $user,
                'token' => $bearerToken
            ]);
        }

        
    }
}
