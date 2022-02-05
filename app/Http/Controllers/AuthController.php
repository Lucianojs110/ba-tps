<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Airlock\HasApiTokens;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request){

        if (!Auth::attempt($request->only('email', 'password'))) {
         //log event//    
        Log::channel('events')->info('Failed login: ip address: '.$request->ip());
        return response()->json([
        'message' => trans('auth.failed')
                   ], 200);
               }
        
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        //log event//
        Log::channel('events')->info('Successful entry: ip address: '.$request->ip().' | User id: '.$user->id);
        return response()->json([  
                'access_token' => $token,
                'user' => $user,
        ]);
        }

        public function logout(Request $request)
        {
            $user = request()->user();
            $user->tokens()->delete();
             //log event//
             Log::channel('events')->info('Close session: ip address: '.$request->ip().'| User id: '.$user->id);
            return response()->json([
     
                'message' => 'Se ha cerrado sesión correctamente',
        ]);
        }
}
