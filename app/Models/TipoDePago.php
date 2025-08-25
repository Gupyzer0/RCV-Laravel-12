<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDePago extends Model
{
    use SoftDeletes;
    
    protected $table = 'tipos_de_pago';
}
