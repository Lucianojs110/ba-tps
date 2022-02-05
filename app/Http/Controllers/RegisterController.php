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
    public function store_pyme(UserPymeRegisterRequest $request){
 
        $user = new User();
        $user->name = request('name');
        $user->last_name = request('last_name');
        $user->email = request('email');
        $user->password = Hash::make(request('password'));
        $user->save();
        $user->assign_role(2);

        $data_pyme = new DataPyme();
        $data_pyme->id_user = $user->id;
        $data_pyme->provincia = request('provincia');
        $data_pyme->localidad = request('localidad');
        $data_pyme->direccion = request('direccion');
        $data_pyme->actividad = request('actividad');
        $data_pyme->rubro = request('rubro');
        $data_pyme->nivel_desarrollo = request('nivel_desarrollo');
        $data_pyme->save();

        $userRes = User::With('roles')->findorFail($user->id);

        //log event//
        Log::channel('events')->info('Registered user: ip address: '.$request->ip().' | User id: '.$request->user().' | User id create: '.$user);

        return response()->json([
            'message' => 'Se ha creado el usuario correctamente',
            'user' => $userRes
        ]);

    }


    public function store_eae(UserEaeRegisterRequest $request){
 
        $user = new User();
        $user->name = request('name');
        $user->last_name = request('last_name');
        $user->email = request('email');
        $user->password = Hash::make(request('password'));
        $user->save();
        $user->assign_role(3);

        $data_eae = new DataEae();
        $data_eae->id_user = $user->id;
        $data_eae->provincia = request('provincia');
        $data_eae->localidad = request('localidad');
        $data_eae->direccion = request('direccion');
        $data_eae->servicios_generales = request('servicios_generales');
        $data_eae->servicios_especificos = request('servicios_especificos');
        
        $data_eae->save();

        $userRes = User::With('roles')->findorFail($user->id);

        //log event//
        Log::channel('events')->info('Registered user: ip address: '.$request->ip().' | User id: '.$request->user().' | User id create: '.$user);

        return response()->json([
            'message' => 'Se ha creado el usuario correctamente',
            'user' => $userRes
        ]);

    }
}
