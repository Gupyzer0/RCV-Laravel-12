<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rreport extends Model
{
    protected $table = 'payments_report';
    protected $fillable = [
        'id_policy','user_id', 'amount', 'reference_number'
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class, 'policy_id');
    }
}
