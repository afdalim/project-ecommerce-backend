<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Midtrans\Config;
use Midtrans\Snap;


class AuthController extends Controller
{


    public function register(Request $request)
    {


        $request->validate([


            'name' =>
            'required|string|max:255',


            'email' =>
            'required|email|unique:users,email',


            'password' =>
            'required|min:8|confirmed',


        ]);




        $user =
        User::create([


            'name' =>
            $request->name,


            'email' =>
            $request->email,


            'password' =>
            Hash::make(
                $request->password
            ),


            'role' =>
            'customer',


        ]);




        $token =
        JWTAuth::fromUser(
            $user
        );




        return response()->json([


            'message' =>
            'User registered successfully',


            'user' =>
            $user,


            'token' =>
            $token,


        ],201);


    }









    public function login(Request $request)
    {


        $credentials =
        $request->validate([


            'email' =>
            'required|email',


            'password' =>
            'required',


        ]);




        try{


            if(
                !$token =
                JWTAuth::attempt(
                    $credentials
                )
            ){


                return response()->json([

                    'message' =>
                    'Invalid credentials'

                ],401);


            }



        }catch(JWTException $e){

    return response()->json([
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ],500);

}






        return response()->json([


            'message' =>
            'Login successful',


            'token' =>
            $token,


            'user' =>
            auth()->user(),


        ]);


    }









    public function logout()
    {


        try{


            JWTAuth::invalidate(
                JWTAuth::getToken()
            );



            return response()->json([


                'message' =>
                'Logout successful'


            ]);



        }catch(JWTException $e){



            return response()->json([


                'message' =>
                'Failed to logout'


            ],500);



        }


    }










    public function me()
    {


        return response()->json(
            auth()->user()
        );


    }










    public function updateProfile(Request $request)
    {


        $request->validate([


            'name' =>
            'required|string|max:255',


            'phone' =>
            'nullable|string|max:20',


            'gender' =>
            'nullable|in:male,female',


            'birth_date' =>
            'nullable|date',


            'bio' =>
            'nullable|string|max:500',


        ]);






        $user =
        auth()->user();






        $user->update([


            'name' =>
            $request->name,


            'phone' =>
            $request->phone,


            'gender' =>
            $request->gender,


            'birth_date' =>
            $request->birth_date,


            'bio' =>
            $request->bio,


        ]);






        return response()->json([


            'message' =>
            'Profile updated',


            'user' =>
            $user,


        ]);


    }










    public function refresh()
    {


        return response()->json([


            'token' =>
            JWTAuth::refresh(
                JWTAuth::getToken()
            )


        ]);


    }




}