<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produccion;
use App\Models\Stock;
use App\Models\Venta;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProduccionController extends Controller
{
    
    public function index()
    {
        return Produccion::with('producto')->get();
    }

    public function store(Request $request)
    {
        
    
        $produccion = new Produccion();
    
        $produccion->id_producto = request('id_producto');
        $produccion->acciones = request('acciones');
        $produccion->cantidad = request('cantidad');
        $produccion->estado = 'en proceso';
        $produccion->hora = request('hora');
        $produccion->fecha = request('fecha');
        $produccion->save();

        //log event//
        Log::channel('events')->info('Ingreso nueva Produccion: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | Ingreso: ' .$produccion);

        $Res = Produccion::findorFail($produccion->id);
        

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

    

        $produccion = Produccion::findorFail($id);
    
    
        $produccion->id_producto = request('id_producto');
        $produccion->acciones = request('acciones');
        $produccion->cantidad = request('cantidad');
        $produccion->estado = 'en proceso';
        $produccion->hora = request('hora');
        $produccion->fecha = request('fecha');
        $produccion->update();

        //log event//
        Log::channel('events')->info('actualizo  Produccion: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | Ingreso: ' .$produccion);

        $Res = Produccion::findorFail($produccion->id);
        

        return response()->json([
            'message' => 'Se actualizo la Produccion correctamente',
            'Produccion' => $Res
        ]);
        
    }
  
    public function finalizar(Request $request, $id)
    {
        $date = Carbon::now();
        $hora = $date->toTimeString();

        $produccion = Produccion::findorFail($id);
        $produccion->estado = 'finalizado';
        $produccion->hora = request('hora');
        $produccion->fecha = request('fecha');
        $produccion->update();

        /* Log::channel('events')->info('Produccion'.$produccion).
                                    ' | Request del front: '.$request->all(); */
        
        if($produccion->acciones == 'Procesar'){
            if($produccion->id_producto == 1){ //soja

                 $stock = new Stock();
                 $stock->id_producto = 7;
                 $stock->cantidad = request('cantidad_aceite');
                 $stock->save();

                 $stock2 = new Stock();
                 $stock2->id_producto = 9;
                 $stock2->cantidad = request('cantidad_expeler');
                 $stock2->save();

            }
            elseif($produccion->id_producto == 2){//Girasol

                 $stock = new Stock();
                 $stock->id_producto = 8;
                 $stock->cantidad = request('cantidad_aceite');
                 $stock->save();

                 $stock = new Stock();
                 $stock->id_producto = 10;
                 $stock->cantidad = request('cantidad_expeler');
                 $stock->save();

            }

        //log event//
        Log::channel('events')->info('Accion Procesar'.
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | Produccion: ' .$produccion);

           
        }
        else{//desactivar

            if($produccion->id_producto == 1){ //soja

                $stock = new Stock();
                $stock->id_producto = 4; //soja desactivado
                $stock->cantidad = request('cantidad_desactivada');
                $stock->save();
    
            }

            if($produccion->id_producto == 2){ //Girasol

                $stock = new Stock();
                $stock->id_producto = 5; //soja desactivado
                $stock->cantidad = request('cantidad_desactivada');
                $stock->save();

            }

            if($produccion->id_producto == 3){ //Maiz

                $stock = new Stock();
                $stock->id_producto = 6; //Maiz desactivado
                $stock->cantidad = request('cantidad_desactivada');
                $stock->save();

           }


           //log event//
            Log::channel('events')->info('Accion Desactivar'.
            ' | Usuario id: '.$request->user()->id.
            ' | Produccion: ' .$produccion);

        }
        

        

        $Res = Produccion::findorFail($produccion->id);

        return response()->json([
            'message' => 'Se ha creado la Produccion correctamente',
            'Produccion' => $Res
        ]);

    }

    public function ventas_store(Request $request, $id){

        $date = Carbon::now();
        $hora = $date->toTimeString();
        $fecha = $date->toDateString();

        $produccion = Produccion::findorFail($id);
        $produccion->estado = 'Finalizado';
        $produccion->hora = $hora;
        $produccion->fecha = $fecha;
        $produccion->update();


        if($produccion->id_producto == 1){ //soja

            $venta = new Venta();
            $venta->id_producto = 4; //soja desactivado
            $venta->id_cliente = request('id_cliente');
            $venta->fecha = request('fecha_venta');
            $venta->cantidad = request('cantidad_desactivada');
            $venta->venta_directa = 'T';
            $venta->save();

        }

        if($produccion->id_producto == 2){ //Girasol

            $venta = new Venta();
            $venta->id_producto = 5; //girasol desactivado
            $venta->id_cliente = request('id_cliente');
            $venta->fecha = request('fecha_venta');
            $venta->cantidad = request('cantidad_desactivada');
            $venta->venta_directa = 'T';
            $venta->save();

        }

        if($produccion->id_producto == 3){ //Maiz

            $venta = new Venta();
            $venta->id_producto = 6; //Maiz desactivado
            $venta->id_cliente = request('id_cliente');
            $venta->fecha = request('fecha_venta');
            $venta->cantidad = request('cantidad_desactivada');
            $venta->venta_directa = 'T';
            $venta->save();

       }




       $Res = Produccion::findorFail($produccion->id);

        return response()->json([
            'message' => 'Se ha creado la Venta correctamente',
            'Produccion' => $Res
        ]);
        
    }

   
    public function destroy($id)
    {
        $prod = Produccion::find($id);
        $prod->delete();
        
        //log event//
        Log::channel('events')->info('Eliminar produccion: '.$prod);
        
        return response()->json([
            'message' => 'Se ha elminado el registro correctamente',
            'produccion' => $prod
        ]);
    }
}
