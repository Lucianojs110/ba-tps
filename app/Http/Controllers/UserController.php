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
        //policy//
        $this->authorize('viewAll', User::class);

        return  User::With('roles')->get();
    }

    public function store(UserRegisterRequest $request){

        //policy//
        $this->authorize('create', User::class);
        
        $user = new User();
        $user->name = request('name');
        $user->last_name = request('last_name');
        $user->email = request('email');
        $user->password = Hash::make(request('password'));
        $user->save();
        $user->assign_role(request('role'));

        $token = $user->createToken('auth_token')->plainTextToken;

        $userRes = User::With('roles')->findorFail($user->id);

        //log event//
        Log::channel('events')->info('Registered user: ip address: '.$request->ip().' | User id: '.$request->user().' | User id create: '.$user);

        return response()->json([
            'access_token' => $token,
            'message' => 'Se ha creado el usuario correctamente',
            'user' => $userRes
        ]);

    }

    
    public function show($id)
    {
        //policy//
        $this->authorize('view', User::class);

        return  User::With('roles')->findorFail($id);
    }

    public function update(UserEditRequest $request, User $user)
    {
        //policy//
        $this->authorize('update', User::class);
        
        $user->name = $request->get('name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');

        $pass = $request->get('password');
        if ($pass != null) {
            $user->password = Hash::make(request('password'));
        } else {
            unset($user->password);
        }
        //if you don't have a role, we assign///
        $role = $user->roles;
        if (count($role) > 0) {
            $role_id = $role[0]->id;
            User::find($user->id)->roles()->updateExistingPivot($role_id, ['role_id' => $request->get('role')]);
        } else {
            $user->assign_role($request->get('role'));
        }
        $user->update();
        $userRes = User::With('roles')->findorFail($user->id);
        
        //log event//
        Log::channel('events')->info('Update User: ip address: '.$request->ip().' | User id: '.$request->user()->id.' | User Update id: '.$user->id);

        return response()->json([
            'message' => 'Se ha actualizado el usuario correctamente',
            'user' => $userRes
        ]);
       
    }

    public function destroy(Request $request, User $user)
    {
        //policy//
        $this->authorize('destroy', User::class);

        //log event//
        Log::channel('events')->info('Delete User: ip address: '.$request->ip().' | User id: '.$request->user()->id.' | User Delete id: '.$user->id);
        $user->delete();
        return response()->json([
            'message' => 'Se ha elminado el usuario correctamente',
            'user' => $user
        ]);
    }
}
