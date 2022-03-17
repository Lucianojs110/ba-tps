<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produccion;
use Illuminate\Support\Facades\Log;

class ProduccionController extends Controller
{
    
    public function index()
    {
        return Produccion::with('producto')->get();
    }

    public function store(Request $request)
    {
        $produccion = new Produccion();
        $stock = new Stock();


        if(request('acciones') == 'procesar'){

            

            
        }else{

            $produccion->id_producto = request('id_producto');
            $produccion->acciones = 'desactivar';
            $produccion->cantidad = request('cantidad');
            $produccion->estado = 'finalizado';
            $produccion->save();

        

            //log event//
            Log::channel('events')->info('Ingreso nueva Produccion: ip address: '.$request->ip().
                                        ' | Usuario id: '.$request->user()->id.
                                        ' | Ingreso: ' .$produccion);

            $Res = Produccion::findorFail($produccion->id);
        }
        

        return response()->json([
            'message' => 'Se ha creado la Produccion correctamente',
            'Produccion' => $Res
        ]);

    }

    
    public function show($id)
    {
        return  Produccion::findorFail($id);
    }

  
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }
}
