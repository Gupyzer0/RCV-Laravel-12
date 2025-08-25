<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class Vehicle extends Model
{
	use SoftDeletes; //, LogsActivity;

    protected static $logName = "Vehiculo";
    protected static $logAttributes = ['brand', 'model'];
    protected $table = "vehicles";
    protected $fillable = ['model', 'brand', 'type_id'];
    protected $dates = ['deleted_at'];

    public function types()
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
    	return $this->belongsTo(Admin::class, 'admin_id');
    }
}