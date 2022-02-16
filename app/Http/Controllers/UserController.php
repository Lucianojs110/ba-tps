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

    public function store(/* UserRegister */Request $request){
        /* Log::channel('events')->info('request'.$request->get('id_role')); */
    
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
        //Log::channel('events')->info('Registered user: ip address: '.$request->ip().' | User id: '.$request->user().' | User id create: '.$user);

        return response()->json([
            'message' => 'Se ha creado el usuario correctamente',
            'user' => $userRes
        ]);

    }

    
    public function show($id)
    {
        return  User::With('role')->findorFail($id);
    }

    public function update(UserEditRequest $request, User $user)
    {
    
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
        
        $user->update();
        $userRes = User::With('role')->findorFail($user->id);
        
        //log event//
        //Log::channel('events')->info('Update User: ip address: '.$request->ip().' | User id: '.$request->user()->id.' | User Update id: '.$user->id);

        return response()->json([
            'message' => 'Se ha actualizado el usuario correctamente',
            'user' => $userRes
        ]);
       
    }

    public function destroy($id)
    {
        //policy//
        //$this->authorize('destroy', User::class);

        //log event//
        //Log::channel('events')->info('Delete User: ip address: '.$request->ip().' | User id: '.$request->user()->id.' | User Delete id: '.$user->id);
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'message' => 'Se ha elminado el usuario correctamente',
            'user' => $user
        ]);
    }
}
