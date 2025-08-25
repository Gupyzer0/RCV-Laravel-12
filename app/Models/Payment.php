<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Payment extends Model
{
    //use LogsActivity;

    protected static $logName = "Pago";
    protected $table = 'payments';
    protected $fillable = [
        'tipo_de_pago_id',
    	'bill','type','name','office','user_id',
        'total','profit_percentage','total_payment',
        'from','until',
    ];

    // Relaciones

    /**
     * Polizas asociadas (que fueron pagadas) con este pago
     */
    public function policies()
    {
        return $this->hasMany(Policy::class, 'payment_id');
    }

    /**
     * Usuario asociado a este pago
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Administrador (supervisor | moderator | type) que hizo el pago
     */
    public function administrador()
    {
        return $this->belongsTo(Admin::class, 'type');
    }

    /**
     * Relación con el tipo de pago
     */
    public function tipo_de_pago()
    {
        return $this->belongsTo(TipoDePago::class, 'tipo_de_pago_id');
    }

    // Mutadores
    /**
     * Obtiene el comprobante
     * Si es un pago manual retorna la url del soporte.
     * Si es un pago automático retorna el JSON decodificado (array) del pago almacenado en la base de datos.
     */
    public function getComprobanteAttribute() {
        $documento = 'Sin Comprobante';

        switch($this->tipo_de_pago_id)
        {
            case 1: // Pago manual
                $documento = $this->bill;
                break;

            case 2 : // Pago automático
                $documento = json_decode($this->bill);
                break;
        }
        return $documento;
    }
}
