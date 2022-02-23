<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserEditRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{


    public function index()
    {       
       
        return  User::With('role')->get();
    }

    public function store(Request $request){
    
    
        $user = new User();
        $user->name = request('name');
        $user->last_name = request('last_name');
        $user->email = request('email');
        $user->id_role = request('rol');
        $user->password = Hash::make(request('password'));
        Log::channel('events')->info('request'.$request);
        $user->save();
       
        $userRes = User::With('role')->findorFail($user->id);

        //log event//
        Log::channel('events')->info('Usuario registrado: ip address: '.$request->ip().' | Usuario id: '.$request->user().' | Usuario id creado: '.$user);

        return response()->json([
            'message' => 'Se ha creado el usuario correctamente',
            'user' => $userRes
        ]);

    }

    
    public function show($id)
    {
        return  User::With('role')->findorFail($id);
    }

    public function update(Request $request,$id)
    {
        $user = User::find($id);

        $user->name = $request->get('name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->id_role = $request->get('rol');
        $pass = $request->get('password');
        if ($pass != null) {
            $user->password = Hash::make(request('password'));
        } else {
            unset($user->password);
        }
        
        Log::channel('events')->info('request'.$request);
        $user->update();
        $userRes = User::With('role')->findorFail($user->id);
    

        return response()->json([
            'message' => 'Se ha actualizado el usuario correctamente',
            'user' => $userRes
        ]);
       
    }

    public function destroy($id)
    {
    
        $user = User::find($id);
        $user->delete();
        
        //log event//
        Log::channel('events')->info('Delete User: '.$user->id);
        
        return response()->json([
            'message' => 'Se ha elminado el usuario correctamente',
            'user' => $user
        ]);
    }
}
