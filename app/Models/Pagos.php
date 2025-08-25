<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;

class Pagos extends Model
{
    use SoftDeletes;//, LogsActivity;

    protected $table = "payments_report";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'policy_id',
        'paymentt',
        'id_bank',
        'amount',
        'currency',
        'referenceNumber',
        'ciNumber',
        'phoneNumber'
    ];

    public function bank()
    {
    	return $this->belongsTo(Bank::class, 'bank_id', 'id')->withTrashed();
    }

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function policies(){
        return $this->belongsTo(Policy::class, 'policy_id', 'id')->withTrashed();
    }
}
