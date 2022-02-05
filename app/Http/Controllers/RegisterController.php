<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DataPyme;
use App\Models\DataEae;
use App\Http\Requests\UserPymeRegisterRequest;
use App\Http\Requests\UserEaeRegisterRequest;
use App\Http\Requests\UserEditRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function store(UserPymeRegisterRequest $request){
 
        $user = new User();
        $user->name = request('name');
        $user->last_name = request('last_name');
        $user->email = request('email');
        $user->password = Hash::make(request('password'));
        $user->save();
        $user->assign_role(2);

     
        $userRes = User::With('roles')->findorFail($user->id);

        //log event//
        Log::channel('events')->info('Registered user: ip address: '.$request->ip().' | User id: '.$request->user().' | User id create: '.$user);

        return response()->json([
            'message' => 'Se ha creado el usuario correctamente',
            'user' => $userRes
        ]);

    }

}
