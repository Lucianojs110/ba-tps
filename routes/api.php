<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*//////AUTH ROUTES/////*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

/*//////USUARIOS/////*/
Route::apiResource('users', 'UserController')->middleware('auth:sanctum');

/*//////ROLES/////*/
Route::apiResource('roles', 'RoleController')->middleware('auth:sanctum');

/*//////PROVEEDOR/////*/
Route::apiResource('proveedores', 'ProveedorController')->middleware('auth:sanctum');

/*//////CLIENTES/////*/
Route::apiResource('clientes', 'ClienteController')->middleware('auth:sanctum');

Route::put('/filtroscliente', 'ClienteController@filtros_cliente')->middleware('auth:sanctum');

/*//////INGRESO/////*/
Route::apiResource('ingreso', 'IngresoController')->middleware('auth:sanctum');

Route::put('/filtrosingreso', 'IngresoController@filtros_cala')->middleware('auth:sanctum');

/*//////PRODUCTOS/////*/
Route::apiResource('productos', 'ProductoController')->middleware('auth:sanctum');

/*//////PRODUCCION/////*/
Route::apiResource('produccion', 'ProduccionController')->middleware('auth:sanctum');

Route::put('/finalizar/{id}', 'ProduccionController@finalizar')->middleware('auth:sanctum');

Route::put('/vender/{id}', 'ProduccionController@ventas_store')->middleware('auth:sanctum');

Route::put('/filtrosproduccion', 'ProduccionController@filtros_produccion')->middleware('auth:sanctum');

/*//////STOCK/////*/
Route::apiResource('stock', 'StockController')->middleware('auth:sanctum');

/*//////TRANSPORTISTAS/////*/
Route::apiResource('transportistas', 'TransportistaController')->middleware('auth:sanctum');


/*//////Ventas/////*/
Route::apiResource('ventas', 'VentasController')->middleware('auth:sanctum');




