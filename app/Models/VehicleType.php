<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class VehicleType extends Model
{
	use SoftDeletes;//, LogsActivity;

    protected $table = "vehicle_types";
    protected static $logName = "Tipo de Vehiculo";
    protected static $logAttributes = ['type'];
    protected $fillable = ['type'];
    protected $dates = ['deleted_at'];
}
