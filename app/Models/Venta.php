<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table = 'ventas';
    public $primaryKey = 'id';
    public $timestamps = false;

    public function producto()
    {
        return $this->hasOne('App\Models\Producto', 'id', 'id_producto');
    }

    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'id_cliente');
    }

 
}