<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ForeignUnit extends Model
{
    //use LogsActivity;

    protected static $logName = "Divisa";
    protected $table = 'foreign_units';

}
