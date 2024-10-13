<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiRegisterContoller extends Controller
{
    public function register(Request $request){
        $validationRule = [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => "required|string|in:student,guardian"
        ];

        $validation = Validator::make($request->all(), $validationRule);
        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validation->errors()
            ]);
        }
        $userExist = User::where("email", $request->email)->first();
        if ($userExist) {
           return response()->json([
                'status'=> false,
                'message' => 'This email has already been used please login'
           ]);
        }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password) ;
        $user->role = $request->role;
        $user->save();


        $bearerToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registration Succesful',
            'user' => $user,
            'token' => $bearerToken
        ]);
    }
}
