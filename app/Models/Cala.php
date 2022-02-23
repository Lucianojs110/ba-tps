<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cala extends Model
{
    use HasFactory;
    protected $table = 'calas';
    public $primaryKey = 'id';
    public $timestamps = true;
}
