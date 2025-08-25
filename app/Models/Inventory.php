<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Inventory extends Model
{
    use SoftDeletes; //, LogsActivity;

    protected static $logName = "Inventario";
    protected $table = "inventory";
    protected $dates = ['deleted_at'];
    protected $fillable = ['descripcion', 'marca', 'modelo', 'serial', 'observacion'];

    public function user(){
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function office(){
        return $this->belongsTo(Office::class, 'office_id', 'id')->withTrashed();
    }
}
