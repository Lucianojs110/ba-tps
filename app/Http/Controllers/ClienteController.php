<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Log;


class ClienteController extends Controller
{
    public function index()
    {       
        $cuit= request('cuit');
        $nombre = request('nombre_cliente');

        $cliente = Cliente::get();

        if(!empty($cuit)){

            $cliente = Cliente::where('num_doc',$cuit)
                                ->get();
        }

        if(!empty($nombre)){

            $cliente = Cliente::where('nombre','like',"%$nombre%")
                                ->get();
        }

        return $cliente;
    }

    public function store(Request $request){

        $cliente = new Cliente();
        $cliente->nombre = request('nombre');
        $cliente->tipo_doc = request('tipo_doc');
        $cliente->num_doc = request('num_doc');
        $cliente->ciudad = request('ciudad');
        $cliente->direccion = request('direccion');
        $cliente->email = request('email');
        $cliente->telefono = request('telefono');
        $cliente->save();

        //log event//
        Log::channel('events')->info('Crear cliente: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | cliente: ' .$cliente);

        return response()->json([
            'message' => 'Se ha creado el cliente correctamente',
            'cliente' => Cliente::findorFail($cliente->id)
        ]);

    }

    public function show($id)
    {
        return  Cliente::findorFail($id);
    }

    public function update(Request $request,$id)
    {
        $cliente = Cliente::find($id);

        $cliente->nombre = request('nombre');
        $cliente->tipo_doc = request('tipo_doc');
        $cliente->num_doc = request('num_doc');
        $cliente->ciudad = request('ciudad');
        $cliente->direccion = request('direccion');
        $cliente->email = request('email');
        $cliente->telefono = request('telefono');
        $cliente->update();
        
        //log event//
        Log::channel('events')->info('Actualizar cliente: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | cliente: ' .$cliente);
        
        $Res = Cliente::findorFail($cliente->id);
    

        return response()->json([
            'message' => 'Se ha actualizado el cliente correctamente',
            'cliente' => $Res
        ]);
       
    }

    public function destroy($id)
    {
    
        $cliente = Cliente::find($id);
        $cliente->delete();
        
        //log event//
        Log::channel('events')->info('Eliminar Cliente: '.$cliente);
        
        return response()->json([
            'message' => 'Se ha elminado el cliente correctamente',
            'cliente' => $cliente
        ]);
    }

}
