<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transportista;
use Illuminate\Support\Facades\Log;


class TransportistaController extends Controller
{
    public function index()
    {       
        return Transportista::get();
    }

    public function store(Request $request)
    {

        $transportista = new Transportista();
        $transportista->carnet_prof = request('carnet_prof');
        $transportista->fechav_cprof = request('fechav_cprof');
        $transportista->carnet_cocatra = request('carnet_cocatra');
        $transportista->fechav_ccocatra = request('fechav_ccocatra');
        $transportista->cedula_cha = request('cedula_cha');
        $transportista->fechav_cha = request('fechav_cha');
        $transportista->cedula_sem = request('cedula_sem');
        $transportista->fechav_sem = request('fechav_sem');
        $transportista->vtv_cha = request('vtv_cha');
        $transportista->fechav_vtvcha = request('fechav_vtvcha');
        $transportista->vtv_sem = request('vtv_sem');
        $transportista->fechav_vtvsem = request('fechav_vtvsem');
        $transportista->constancia_ruta = request('constancia_ruta');
        $transportista->fechav_construt = request('fechav_construt');
        $transportista->constancia_senasa = request('constancia_senasa');
        $transportista->fechav_senasa = request('fechav_senasa');
        $transportista->fechav_segurocha = request('fechav_segurocha');
        $transportista->fechav_cha = request('fechav_cha');
        $transportista->seguro_cha = request('seguro_cha');
        $transportista->fechav_segurocha = request('fechav_segurocha');
        $transportista->seguro_semi = request('seguro_semi');
        $transportista->fechav_segsemi = request('fechav_segsemi');
        $transportista->rt_chofer = request('rt_chofer');
        $transportista->fechav_rt = request('fechav_rt');
        $transportista->nombre_transportista = request('nombre_transportista');
        $transportista->dni_chofer = request('dni_chofer');
        $transportista->cuit_chofer = request('cuit_chofer');
     
        $transportista->save();

        //log event//
        Log::channel('events')->info('Store Transportista: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | Transportista: ' .$transportista);

        $Res = Transportista::findorFail($transportista->id);
        

        return response()->json([
            'message' => 'Se ha creado el Transportista correctamente',
            'Produccion' => $Res
        ]);

    }

    public function show($id)
    {
        return  Transportista::findorFail($id);
    }

    public function update(Request $request,$id)
    {
        $transportista = Transportista::findorFail($id);

        $transportista->carnet_prof = request('carnet_prof');
        $transportista->fechav_cprof = request('fechav_cprof');
        $transportista->carnet_cocatra = request('carnet_cocatra');
        $transportista->fechav_ccocatra = request('fechav_ccocatra');
        $transportista->cedula_cha = request('cedula_cha');
        $transportista->fechav_cha = request('fechav_cha');
        $transportista->cedula_sem = request('cedula_sem');
        $transportista->fechav_sem = request('fechav_sem');
        $transportista->vtv_cha = request('vtv_cha');
        $transportista->fechav_vtvcha = request('fechav_vtvcha');
        $transportista->vtv_sem = request('vtv_sem');
        $transportista->fechav_vtvsem = request('fechav_vtvsem');
        $transportista->constancia_ruta = request('constancia_ruta');
        $transportista->fechav_construt = request('fechav_construt');
        $transportista->constancia_senasa = request('constancia_senasa');
        $transportista->fechav_senasa = request('fechav_senasa');
        $transportista->fechav_segurocha = request('fechav_segurocha');
        $transportista->fechav_cha = request('fechav_cha');
        $transportista->seguro_cha = request('seguro_cha');
        $transportista->fechav_segurocha = request('fechav_segurocha');
        $transportista->seguro_semi = request('seguro_semi');
        $transportista->fechav_segsemi = request('fechav_segsemi');
        $transportista->rt_chofer = request('rt_chofer');
        $transportista->fechav_rt = request('fechav_rt');
        $transportista->nombre_transportista = request('nombre_transportista');
        $transportista->dni_chofer = request('dni_chofer');
        $transportista->cuit_chofer = request('cuit_chofer');
     
        $transportista->update();


        //log event//
        Log::channel('events')->info('Update Transportista: ip address: '.$request->ip().
                                    ' | Usuario id: '.$request->user()->id.
                                    ' | Transportista: ' .$transportista);

        $Res = Transportista::findorFail($transportista->id);
        

        return response()->json([
            'message' => 'Se ha actualizado el Transportista correctamente',
            'Produccion' => $Res
        ]);
    }

    public function destroy($id)
    {
        $trans = Transportista::find($id);
        $trans->delete();

      
        //log event//
        Log::channel('events')->info('Eliminar transportista: '.$trans);

        return response()->json([
            'message' => 'Se ha eliminado el Transportista correctamente',
            'Produccion' => $trans
        ]);
       
    }
}
