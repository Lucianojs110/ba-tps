<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rubro;
use App\Models\Actividad;

class DataPymeController extends Controller
{
    public function rubros()
    {       
        //policy//
        $this->authorize('viewAll', User::class);

        return  Rubro::get();
    }

    public function Actividad()
    {       
        //policy//
        $this->authorize('viewAll', User::class);
        return  Actividad::get();
    }
}
