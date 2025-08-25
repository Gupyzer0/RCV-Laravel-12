<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class VehicleClass extends Model
{
    use SoftDeletes;//, LogsActivity;

    protected $table = "vehicle_classes";
    protected static $logName = "Clase de Vehículo";
    protected static $logAttributes = ['class'];
    protected $fillable = ['class'];
    protected $dates = ['deleted_at'];
}
