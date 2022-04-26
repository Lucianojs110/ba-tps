<?php

namespace App\Http\Controllers;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Afip;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function index()
    {       
        return Venta::with('producto', 'cliente')->get();
    }

    public function store(Request $request)
    {
        
        $id_producto = request('id_producto');
        $cantidad = request('cantidad');
        
        $producto = Producto::FindOrFail($id_producto);

        $neto = $cantidad * $producto->precio_unitario;
        
        $ventas = new Venta();

        $ventas->id_producto = request('id_producto');
        $ventas->id_cliente = request('id_cliente');
        $ventas->fecha = request('fecha');
        $ventas->cantidad = request('cantidad');
        $ventas->precio_unitario = $producto->precio_unitario;
        $ventas->neto = $neto;
        $ventas->iva = $neto*0.21;
        $ventas->total = $neto + $ventas->iva;
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
        return  Venta::with('producto', 'cliente')->findorFail($id);
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

    public function caesolicitud(Request $request,$id_venta){


        $venta = Venta::findorFail($id_venta);
        $id_cliente = $venta->id_cliente;

        $cliente = Cliente::where('id',$id_cliente)->first(); 
        $tipo_iva = $cliente->iva;

        $punto_v = 7;
            
        $options = [                    //options es un array con el CUIT (de la empresa que esta vendiendo)
            'CUIT' => 30716605872,
            'production' => True,
            'cert' => 'TPS.crt',
            'key' => 'llave.key',
            ];
    

        if($tipo_iva == 'RESPONSABLE INSCRIPTO'){


            $tipoCbteNumero = 1; //factura B
        
            $ImpTotal = 1;
            $afip = new Afip($options);
            $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_v, $tipoCbteNumero);
            $info = $afip->ElectronicBilling->GetVoucherInfo(1 ,$punto_v , $tipoCbteNumero);
            
            $numComp = $last_voucher + 1;
            
            
            $ImpNeto = $ImpTotal;  //$ImpTotal/1.21;
            $ImpNeto = number_format((float)$ImpNeto, 2, '.', '');
            $ImpIVA = 0;            //$ImpTotal - $ImpNeto
            $ImpIVA = number_format((float)$ImpIVA, 2, '.', '');

            $ImpTot = $ImpNeto; /* + $ImpIVA */
            
            $date = Carbon::now('America/Argentina/Buenos_Aires');
            $date2 = $date->format('Ymd');
            $dateqr = $date->format('Y-m-d');

            

            $data = array(
                'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
                'PtoVta' 	=> $punto_v,  // Punto de venta
                'CbteTipo' 	=> 1,  // Tipo de comprobante (ver tipos disponibles) 
                'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                "FchServDesde" => 'NULL',
                "FchServHasta" => 'NULL',
                "FchVtoPago" => 'NULL',
                'DocTipo' 	=> 80, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                'DocNro' 	=> $cliente->num_doc,  // Número de documento del comprador (0 consumidor final)
                'CbteDesde' 	=> $numComp,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                'CbteHasta' 	=> $numComp,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                'CbteFch' 		=> intval($date2), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                'ImpTotal' 	=> 1.21, // Importe total del comprobante
                'ImpTotConc' 	=> 0,   // Importe neto no gravado
                'ImpNeto' 	=> 1, // Importe neto gravado
                'ImpOpEx' 	=> 0,   // Importe exento de IVA
                'ImpIVA' 	=> 0.21,  //Importe total de IVA ->Si <ImpIVA> es igual a 0 los objetos <IVA> y <AlicIva> solo deben informarse con ImpIVA = 3 (iva 0)
                'ImpTrib' 	=> 0,   //Importe total de tributos
                'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
                'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)
                'Iva' 		=> array( // (Opcional) Alícuotas asociadas al comprobante
                    array(
                        'Id' 		=> 5,  //codigo 3 IVA = 0            // Id del tipo de IVA (5 para 21%)(ver tipos disponibles) 
                        'BaseImp' 	=> 1, // Base imponible
                        'Importe' 	=> 0.21 // Importe 
                    )
                ),  
                
            );
            
            $res = $afip->ElectronicBilling->CreateVoucher($data);
            
            $cae=$res['CAE']; //CAE asignado el comprobante
            $vtocae = $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
        
            $venta = Venta::findorFail($id_venta);
            
            $venta->cae = $cae;
            $venta->vto_cae = $vtocae;
            $cuit = '30716605872';
            
            $num_fac = $last_voucher + 1;
            $venta->num_comprobante = str_pad($punto_v, 4, "0", STR_PAD_LEFT).'-'.str_pad($num_fac, 8, "0", STR_PAD_LEFT);
            
            $data = '{"ver":1,"fecha":'.$date2.',"cuit":'.$cuit.',"ptoVta":'.$punto_v.',"tipoCmp":11,"nroCmp":'.$num_fac.',"importe":'.$ImpTotal.',"moneda":"PES","ctz":1,"tipoDocRec":'.$cliente->tipo_doc.',"nroDocRec":'.$cliente->num_doc.',"tipoCodAut":"E","codAut":'.$cae.'}';
            $data64 = "https://www.afip.gob.ar/fe/qr/?p=".base64_encode($data);
            $venta->codigoQr = $data64;
            $venta->tipo_comprobante = 'A';
            $venta->save();
        
        
            return (["res"=>$res]);
        
        }

        else{

            $tipoCbteNumero = 6; //factura B
        
            $ImpTotal = 1;
            $afip = new Afip($options);
            $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_v, $tipoCbteNumero);
            $info = $afip->ElectronicBilling->GetVoucherInfo(1 ,$punto_v , $tipoCbteNumero);
            
            $numComp = $last_voucher + 1;
            
            
            $ImpNeto = $ImpTotal;  //$ImpTotal/1.21;
            $ImpNeto = number_format((float)$ImpNeto, 2, '.', '');
            $ImpIVA = 0;            //$ImpTotal - $ImpNeto
            $ImpIVA = number_format((float)$ImpIVA, 2, '.', '');

            $ImpTot = $ImpNeto; /* + $ImpIVA */
            
            $date = Carbon::now('America/Argentina/Buenos_Aires');
            $date2 = $date->format('Ymd');
            $dateqr = $date->format('Y-m-d');

            

            $data = array(
                'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
                'PtoVta' 	=> $punto_v,  // Punto de venta
                'CbteTipo' 	=> 6,  // Tipo de comprobante (ver tipos disponibles) 
                'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                "FchServDesde" => 'NULL',
                "FchServHasta" => 'NULL',
                "FchVtoPago" => 'NULL',
                'DocTipo' 	=> 80, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                'DocNro' 	=> $cliente->num_doc,  // Número de documento del comprador (0 consumidor final)
                'CbteDesde' 	=> $numComp,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                'CbteHasta' 	=> $numComp,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                'CbteFch' 		=> intval($date2), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                'ImpTotal' 	=> 1.21, // Importe total del comprobante
                'ImpTotConc' 	=> 0,   // Importe neto no gravado
                'ImpNeto' 	=> 1, // Importe neto gravado
                'ImpOpEx' 	=> 0,   // Importe exento de IVA
                'ImpIVA' 	=> 0.21,  //Importe total de IVA ->Si <ImpIVA> es igual a 0 los objetos <IVA> y <AlicIva> solo deben informarse con ImpIVA = 3 (iva 0)
                'ImpTrib' 	=> 0,   //Importe total de tributos
                'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
                'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)
                'Iva' 		=> array( // (Opcional) Alícuotas asociadas al comprobante
                    array(
                        'Id' 		=> 5,  //codigo 3 IVA = 0            // Id del tipo de IVA (5 para 21%)(ver tipos disponibles) 
                        'BaseImp' 	=> 1, // Base imponible
                        'Importe' 	=> 0.21 // Importe 
                    )
                ),  
                
            );
            
            $res = $afip->ElectronicBilling->CreateVoucher($data);
            
            $cae=$res['CAE']; //CAE asignado el comprobante
            $vtocae = $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
        
            $venta = Venta::findorFail($id_venta);
            
            $venta->cae = $cae;
            $venta->vto_cae = $vtocae;
            $cuit = '30716605872';
            
            $num_fac = $last_voucher + 1;
            $venta->num_comprobante = str_pad($punto_v, 4, "0", STR_PAD_LEFT).'-'.str_pad($num_fac, 8, "0", STR_PAD_LEFT);
            
            $data = '{"ver":1,"fecha":'.$date2.',"cuit":'.$cuit.',"ptoVta":'.$punto_v.',"tipoCmp":11,"nroCmp":'.$num_fac.',"importe":'.$ImpTotal.',"moneda":"PES","ctz":1,"tipoDocRec":'.$cliente->tipo_doc.',"nroDocRec":'.$cliente->num_doc.',"tipoCodAut":"E","codAut":'.$cae.'}';
            $data64 = "https://www.afip.gob.ar/fe/qr/?p=".base64_encode($data);
            $venta->codigoQr = $data64;
            $venta->tipo_comprobante = 'B';
            $venta->save();
        
        
            return (["res"=>$res]);

        }

        
        
         


    }

    public function consultarcuit(Request $request)
    {

        $options = [                    //options es un array con el CUIT (de la empresa que esta vendiendo)
            'CUIT' => 30716605872,
            'production' => True,
            'cert' => 'TPS.crt',
            'key' => 'llave.key',
            ];
    

        $cuit = request('num_doc');
        $afip = new Afip($options);
        $persona = $afip->RegisterScopeFive->GetTaxpayerDetails($cuit);
        $server_status =  $afip->RegisterScopeFive->GetServerStatus();
        
        return response()->json([
            'persona' => $persona,
            'estatus' => $server_status
        ]);
        
    }

  
}
