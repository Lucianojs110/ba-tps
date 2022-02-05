<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataEae extends Model
{
    use HasFactory;
    protected $table = 'data_eae';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'provincia',
        'localidad',
        'direccion',
        'servicios_generales',
        'servicios_especificos',
    ];
    
    public function User(){
        return $this->hasOne('App\Models\User',  'id', 'id_user');
    }
  
}
