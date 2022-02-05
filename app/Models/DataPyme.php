<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPyme extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'data_pymes';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'provincia',
        'localidad',
        'direccion',
        'rubro',
        'actividad',
        'nivel_desarrollo'
    ];

    public function User(){
        return $this->hasOne('App\Models\User',  'id', 'id_user');
    }

}
