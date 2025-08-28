<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class Price extends Model
{
	use SoftDeletes; //, LogsActivity;

    protected $table = "prices";
    protected static $logName = "Precio";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'description', 'class_id',
        'campo', 'campoc', 'campop',
        'campo1', 'campoc1', 'campop1',
        'campo2', 'campoc2', 'campop2',
        'campo3', 'campoc3', 'campop3',
        'campo4', 'campoc4', 'campop4',
        'campo5', 'campoc5', 'campop5',
        'campo6', 'campoc6', 'campop6',
        'total_premium', 'total_all'
    ];

    // public function users(){ // TODO: esta relacion acaso existe???
    //     return $this->belongsToMany(User::class);
    // }

    public function policies(){
        return $this->belongsToMany(Policy::class);
    }

    public function class(){
        return $this->belongsTo(VehicleClass::class, 'class_id')->withTrashed();
    }

    // public function office(){
    //     return $this->belongsTo(Office::class, 'office_id', 'id')->withTrashed();
    // }
}
