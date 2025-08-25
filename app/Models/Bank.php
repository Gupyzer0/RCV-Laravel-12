<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;


class Bank extends Model
{
    use SoftDeletes;//, LogsActivity;
    protected $table = "bank";

    protected $dates = ['deleted_at'];

    protected $fillable = ['name' ];

    // public function bank(){ // como que hasMany de el mismo !??? wtfff no es una categoria o algo asi que tenga hijos . . .
    //     return $this->hasMany('App\Bank');
    // }

}
