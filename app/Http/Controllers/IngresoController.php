<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingreso;
use Illuminate\Support\Facades\Log;


class IngresoController extends Controller
{
    public function index()
    {       
        return Ingreso::with('proveedor', 'tipo_grano')->get();
    }

    public function store(Request $request){

        
        $ingreso = new Ingreso();
        $ingreso->id_proveedor = request('id_proveedor');
        $ingreso->fecha_entrada = request('fecha_entrada');
        $ingreso->hora_entrada = request('hora_entrada');
        $ingreso->id_tipo_grano = request('id_tipo_grano');
        $ingreso->cantidad = request('cantidad');
        $ingreso->condicion = request('condicion');
        $ingreso->humedad = request('humedad');
        $ingreso->densidad = request('densidad');
        $ingreso->num_carta_porte = request('num_carta_porte');
        $ingreso->patente_transporte = request('patente_transporte');
        $ingreso->rechazado = request('rechazado');
        $ingreso->save();

        //log event//
        Log::channel('events')->info('Ingreso productos: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | Ingreso: ' .$ingreso);

        return response()->json([
            'message' => 'Se ha creado el ingreso correctamente',
            'Proveedor' => Ingreso::findorFail($ingreso->id)
        ]);

    }

    public function show($id)
    {
        return  Ingreso::findorFail($id);
    }

    public function update(Request $request,$id)
    {
        $ingreso = Ingreso::find($id);

        $ingreso->id_proveedor = request('id_proveedor');
        $ingreso->fecha_entrada = request('fecha_entrada');
        $ingreso->hora_entrada = request('hora_entrada');
        $ingreso->id_tipo_grano = request('id_tipo_grano');
        $ingreso->cantidad = request('cantidad');
        $ingreso->condicion = request('condicion');
        $ingreso->humedad = request('humedad');
        $ingreso->densidad = request('densidad');
        $ingreso->num_carta_porte = request('num_carta_porte');
        $ingreso->patente_transporte = request('patente_transporte');
        $ingreso->rechazado = request('rechazado');
        $ingreso->update();
        
        //log event//
        Log::channel('events')->info('Actualizar ingreso: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | cliente: ' .$ingreso);
        
        $Res = Ingreso::findorFail($ingreso->id);
    

        return response()->json([
            'message' => 'Se ha actualizado el ingreso correctamente',
            'cliente' => $Res
        ]);
       
    }

    public function destroy($id)
    {
    
        $ingreso = Ingreso::find($id);
        $ingreso->delete();
        
        //log event//
        Log::channel('events')->info('Eliminar ingreso: '.$ingreso);
        
        return response()->json([
            'message' => 'Se ha elminado el ingreso correctamente',
            'cliente' => $ingreso
        ]);
    }
}
