<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Llave extends Model
{
    protected $table = 'llaves';
    protected $guarded = [];
    protected $dates = [
        'expiracion',
    ];
}