<?php

namespace App\Http\Controllers;
use App\Models\Venta;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function index()
    {       
        return Venta::all();
    }

    public function store(Request $request)
    {
        $ventas = new Venta();

        $ventas->id_producto = request('id_producto');
        $ventas->id_cliente = request('id_cliente');
        $ventas->fecha = request('fecha');
        $ventas->cantidad = request('cantidad');
        $ventas->venta_directa = 0;

        $ventas->save();

        //log event//
        Log::channel('events')->info('Store ventas: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | Transportista: ' .$ventas);

        $Res = Venta::findorFail($ventas->id);
        

        return response()->json([
            'message' => 'Se ha creado la Venta correctamente',
            'Produccion' => $Res
        ]);
    }

    public function show($id)
    {
        return  Venta::findorFail($id);
    }

    public function update(Request $request,$id)
    {
        $ventas = Venta::findorFail($id);

        $ventas->id_producto = request('id_producto');
        $ventas->id_cliente = request('id_cliente');
        $ventas->fecha = request('fecha');
        $ventas->cantidad = request('cantidad');
        $ventas->venta_directa = 0;
     
        $ventas->update();


        //log event//
        Log::channel('events')->info('Update Ventas: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | Transportista: ' .$ventas);

        $Res = Venta::findorFail($ventas->id);
        

        return response()->json([
            'message' => 'Se ha actualizado la Venta correctamente',
            'Produccion' => $Res
        ]);
    }

    public function destroy($id)
    {
        $ventas = Venta::find($id);
        $ventas->delete();

      
        //log event//
        Log::channel('events')->info('Eliminar Venta: '.$ventas);

        return response()->json([
            'message' => 'Se ha eliminado la Venta correctamente',
            'Produccion' => $ventas
        ]);
       
    }

  
}
