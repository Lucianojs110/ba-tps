<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGrano extends Model
{
    use HasFactory;
    protected $table = 'productos';
    public $primaryKey = 'id';
    public $timestamps = false;
}
