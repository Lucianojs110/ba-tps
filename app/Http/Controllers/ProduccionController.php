<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produccion;
use App\Models\Stock;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;

class ProduccionController extends Controller
{

    public function index()
    {

        $id_grano = request('grano');
        $accion = request('accion');
        $desde = request('desde');
        $hasta = request('hasta');
        $status = request('estado');

        $prod = Produccion::with('producto')->get();

        if (!empty($id_grano)) {

            $prod = Produccion::with('producto')
                ->where('id_producto', $id_grano)
                ->get();
        }

        if (!empty($accion)) {


            $prod = Produccion::with('producto')
                ->where('acciones', 'like', "%$accion%")
                ->get();
        }

        if (!empty($desde) && !empty($hasta)) {

            $prod = Produccion::with('producto')
                ->where("fecha", ">=", $desde)
                ->where("fecha_fin", "<=", $hasta)
                ->get();
        }

        if (!empty($status)) {

            $prod = Produccion::with('producto')
                ->where('estado', $status)
                ->get();
        }


        return $prod;
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
        Log::channel('events')->info('Ingreso nueva Produccion: ip address: ' . $request->ip() .
            ' | Usuario id: ' . $request->user()->id .
            ' | Ingreso: ' . $produccion);

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
        //$produccion->fecha_fin = 'NULL';
        $produccion->update();

        //log event//
        Log::channel('events')->info('actualizo  Produccion: ip address: ' . $request->ip() .
            ' | Usuario id: ' . $request->user()->id .
            ' | Ingreso: ' . $produccion);

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
        $produccion->hora_fin = request('hora');
        $produccion->fecha_fin = request('fecha');
        $produccion->update();

        /* Log::channel('events')->info('Produccion'.$produccion).
                                    ' | Request del front: '.$request->all(); */

        if ($produccion->acciones == 'Procesar') {
            if ($produccion->id_producto == 1) { //soja

                $stock = new Stock();
                $stock->id_producto = 7;
                $stock->cantidad = request('cantidad_aceite');
                $stock->save();

                $stock2 = new Stock();
                $stock2->id_producto = 9;
                $stock2->cantidad = request('cantidad_expeler');
                $stock2->save();
            } elseif ($produccion->id_producto == 2) { //Girasol

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
            Log::channel('events')->info('Accion Procesar' .
                ' | Usuario id: ' . $request->user()->id .
                ' | Produccion: ' . $produccion);
        } else { //desactivar

            if ($produccion->id_producto == 1) { //soja

                $stock = new Stock();
                $stock->id_producto = 4; //soja desactivado
                $stock->cantidad = request('cantidad_desactivada');
                $stock->save();

                $stock2 = new Stock();
                $stock2->id_producto = 1; //soja
                $stock2->cantidad = $stock->cantidad * -1;
                $stock2->save();
            }

            if ($produccion->id_producto == 2) { //Girasol

                $stock = new Stock();
                $stock->id_producto = 5; //girasol desactivado
                $stock->cantidad = request('cantidad_desactivada');
                $stock->save();

                $stock3 = new Stock();
                $stock3->id_producto = 2; //girasol
                $stock3->cantidad = $stock->cantidad * -1;
                $stock3->save();
            }

            if ($produccion->id_producto == 3) { //Maiz

                $stock = new Stock();
                $stock->id_producto = 6; //Maiz desactivado
                $stock->cantidad = request('cantidad_desactivada');
                $stock->save();

                $stock4 = new Stock();
                $stock4->id_producto = 3; //Maiz
                $stock4->cantidad = $stock->cantidad * -1;
                $stock4->save();
            }


            //log event//
            Log::channel('events')->info('Accion Desactivar' .
                ' | Usuario id: ' . $request->user()->id .
                ' | Produccion: ' . $produccion);
        }




        $Res = Produccion::findorFail($produccion->id);

        return response()->json([
            'message' => 'Se ha creado la Produccion correctamente',
            'Produccion' => $Res
        ]);
    }

    public function ventas_store(Request $request, $id)
    {



        $produccion = Produccion::findorFail($id);
        $produccion->estado = 'Finalizado';
        $produccion->hora_fin = request('hora');
        $produccion->fecha_fin = request('fecha');
        $produccion->update();



        if ($produccion->id_producto == 1) { //soja

            $producto = Producto::FindOrFail(4); //soja desactivada
            $cantidad = request('cantidad_desactivada');
            $neto = $cantidad * $producto->precio_unitario;

            $venta = new Venta();
            $venta->id_producto = 4; //soja desactivada
            $venta->id_cliente = request('id_cliente');
            $venta->fecha = request('fecha');
            $venta->cantidad = request('cantidad_desactivada');
            $venta->precio_unitario = $producto->precio_unitario;
            $venta->neto = $neto;
            $venta->iva = $neto * 0.21;
            $venta->total = $neto + $venta->iva;
            $venta->venta_directa = 1;
            $venta->save();
        }

        if ($produccion->id_producto == 2) { //Girasol


            $producto = Producto::FindOrFail(5); //girasol desactivada
            $cantidad = request('cantidad_desactivada');
            $neto = $cantidad * $producto->precio_unitario;

            /* Log::channel('events')->info('LOG GIRASOL' .
                ' |Precio Unitario: ' . $producto->precio_unitario .
                ' | Producto: ' . $producto); */

            $venta = new Venta();
            $venta->id_producto = 5; //girasol desactivado
            $venta->id_cliente = request('id_cliente');
            $venta->fecha = request('fecha');
            $venta->cantidad = request('cantidad_desactivada');
            $venta->precio_unitario = $producto->precio_unitario;
            $venta->neto = $neto;
            $venta->iva = $neto * 0.21;
            $venta->total = $neto + $venta->iva;
            $venta->venta_directa = 1;
            $venta->save();
        }

        if ($produccion->id_producto == 3) { //Maiz

            $producto = Producto::FindOrFail(6); //Maiz desactivado
            $cantidad = request('cantidad_desactivada');
            $neto = $cantidad * $producto->precio_unitario;

            $venta = new Venta();
            $venta->id_producto = 6; //Maiz desactivado
            $venta->id_cliente = request('id_cliente');
            $venta->fecha = request('fecha');
            $venta->cantidad = request('cantidad_desactivada');
            $venta->precio_unitario = $producto->precio_unitario;
            $venta->neto = $neto;
            $venta->iva = $neto * 0.21;
            $venta->total = $neto + $venta->iva;
            $venta->venta_directa = 1;
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
        Log::channel('events')->info('Eliminar produccion: ' . $prod);

        return response()->json([
            'message' => 'Se ha elminado el registro correctamente',
            'produccion' => $prod
        ]);
    }
}
