<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;

class Accidents extends Model
{
    use SoftDeletes;//, LogsActivity;
    protected $table = "accidents";
    protected $dates = ['deleted_at'];  

     protected $fillable = [
        'policy_id',
        'accident_date',
        'accident_time',
        'accident_type',
        'location',
        'district',
        'description',
        'third_party_name',
        'third_party_dni',
        'third_party_insurance',
        'third_party_plate',
        'photos',
        'police_report',
        'other_documents',
        'bank_id',
        'account_type',
        'account_number',
        'status',
        'registered_by'

    ];

    public function policy()
    {
    	return $this->belongsTo(Policy::class, 'policy_id', 'id')->withTrashed();
    }

    public function bank()
    {
    	return $this->belongsTo(Bank::class, 'bank_id', 'id')->withTrashed();
    }
}
