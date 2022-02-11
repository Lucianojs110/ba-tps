<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {       
        return  Role::get();
    }

    public function store(Request $request){
        $rol = new Role();
        $rol->id = $request->get('id');
        $rol->name = $request->get('name');
        
        $rol->save();

        //log event//
        //Log::channel('events')->info('Registered user: ip address: '.$request->ip().' | User id: '.$request->user().' | User id create: '.$user);

        return response()->json([
            'message' => 'Se ha creado el Rol correctamente',
            'user' => $rol
        ]);
    }
}
