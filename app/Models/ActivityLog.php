<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = "activity_log";

    // TODO Reimplementar todo esto y buscar instalar adecuadamente la libreria responsable
    // de esto  . . .. . 
    // protected $dateFormat = 'Y-m-d H:i:s.u04';

    public function admin(){
    	return $this->belongsTo('App\Admin', 'causer_id', 'id')->withTrashed();
    }

    public function user(){
    	return $this->belongsTo('App\User', 'causer_id', 'id')->withTrashed();
    }
}
