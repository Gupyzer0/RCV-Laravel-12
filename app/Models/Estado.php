<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = "estados";
     protected $primaryKey = 'id_estado';

    public function policies()    {
        return $this->hasMany(Policy::class, 'id_estado');
    }
}
