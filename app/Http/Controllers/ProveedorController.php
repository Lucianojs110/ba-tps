<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{
    public function index()
    {       
        return Proveedor::all();
    }

    public function store(Request $request){

        
        $proveedor = new Proveedor();
        $proveedor->nombre = request('nombre');
        $proveedor->tipo_doc = request('tipo_doc');
        $proveedor->num_doc = request('num_doc');
        $proveedor->ciudad = request('ciudad');
        $proveedor->direccion = request('direccion');
        $proveedor->email = request('email');
        $proveedor->telefono = request('telefono');
        $proveedor->save();

        //log event//
        Log::channel('events')->info('Crear proveedor: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | proveedor: ' .$proveedor);

        return response()->json([
            'message' => 'Se ha creado el proveedor correctamente',
            'Proveedor' => Proveedor::findorFail($proveedor->id)
        ]);

    }

    public function show($id)
    {
        return  Proveedor::findorFail($id);
    }

    public function update(Request $request,$id)
    {
        $proveedor = Proveedor::find($id);

        $proveedor->nombre = request('nombre');
        $proveedor->tipo_doc = request('tipo_doc');
        $proveedor->num_doc = request('num_doc');
        $proveedor->ciudad = request('ciudad');
        $proveedor->direccion = request('direccion');
        $proveedor->email = request('email');
        $proveedor->telefono = request('telefono');
        $proveedor->update();
        
        Log::channel('events')->info('request'.$request);
        
        $provRes = Proveedor::findorFail($proveedor->id);
    

        return response()->json([
            'message' => 'Se ha actualizado el proveedor correctamente',
            'user' => $provRes
        ]);
       
    }

    public function destroy($id)
    {
    
        $proveedor = Proveedor::find($id);
        $proveedor->delete();
        
        //log event//
        Log::channel('events')->info('Eliminar Proveedor: '.$proveedor->id);
        
        return response()->json([
            'message' => 'Se ha elminado el usuario correctamente',
            'user' => $proveedor
        ]);
    }
}
