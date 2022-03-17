<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stock';
    public $primaryKey = 'id';
    public $timestamps = true;

    public function producto()
    {
        return $this->hasOne('App\Models\Producto', 'id', 'id_producto');
    }
}
