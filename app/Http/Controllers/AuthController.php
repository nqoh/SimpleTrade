<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\HttpRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
     use HttpRequest;

   public function register(StoreUserRequest $request)
   {
        $user  =  User::create($request->validated());
        return $this->success([
            'User' => $user,
            'Token' => $user->createToken('Token of '. $user->name)->plainTextToken
        ], 'Account Succesfully created', 201);

   }

   public function login(LoginRequest $request)
   {
        if(Auth::attempt($request->only(['email','password'])))
        {
            $user = User::whereEmail(request('email'))->first();
            return $this->success([
                'User' => $user,
                'Token' => $user->createToken('Token of '. $user->name)->plainTextToken
            ],'You Have Succesfully Login');
        }

          return $this->error('Invalid credential', 401);
   }

   public function logout(Request $request)
   {
       $request->user()->currentAccessToken()->delete();
       return response()->noContent();
   }
}
