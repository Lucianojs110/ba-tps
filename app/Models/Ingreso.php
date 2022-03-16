<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;
    protected $table = 'ingresos';
    public $primaryKey = 'id';
    public $timestamps = true;

    
    public function proveedor()
    {
        return $this->hasOne('App\Models\Proveedor', 'id', 'id_proveedor');
    }

 

    public function producto()
    {
        return $this->hasOne('App\Models\Productos', 'id', 'id_producto');
    }
}
