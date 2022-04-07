<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportista extends Model
{
    use HasFactory;
    protected $table = 'transportistas';
    public $primaryKey = 'id';
    public $timestamps = false;

}
