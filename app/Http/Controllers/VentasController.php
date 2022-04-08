<?php

namespace App\Http\Controllers;
use App\Models\Venta;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function index()
    {       
        return Venta::all();
    }

  
}
