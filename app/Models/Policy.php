<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use App\Models\Scopes\PolizasScope;
use Illuminate\Support\Carbon;

#[ScopedBy([PolizasScope::class])]
class Policy extends Model
{
	use SoftDeletes;//, LogsActivity;

    protected static $logName = "Poliza";
    protected $table = "policies";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'idp', 'type', 'user_id', 'admin_id', 'payment_id', 'price_id','trailer', 'vehicle_id', 'id_estado',
        'id_municipio', 'id_parroquia', 'vehicle_class_id', 'client_address',
        'client_email', 'client_name', 'client_lastname', 'client_ci', 'fecha_n',
        'estadocivil', 'genero', 'client_name_contractor', 'client_lastname_contractor',
        'client_ci_contractor', 'client_phone', 'vehicle_brand', 'vehicle_model',
        'vehicle_type', 'vehicle_registration', 'vehicle_bodywork_serial', 'vehicle_weight',
        'vehicle_motor_serial', 'vehicle_certificate_number', 'vehicle_color', 'vehicle_year',
        'used_for', 'status', 'report', 'expiring_date', 'damage_things', 'premium1',
        'damage_people', 'premium2', 'disability', 'premium3', 'legal_assistance',
        'premium4', 'death', 'premium5', 'medical_expenses', 'premium6', 'damage_passengers',
        'premium7', 'crane', 'limited', 'total_premium', 'total_premiumv', 'total_payment',
        'total_all', 'foreign', 'dolar', 'statusu', 'image_tp', 'image_ci'
    ];

    // Scopes (filtros)
    public function scopeFiltrarNPoliza($query, $filtro) {
        if ($filtro) {
            return $query->where('idp',$filtro);
        }
    }
    
    public function scopeFiltrarCedulaCliente($query, $filtro) {
        if ($filtro) {
            return $query->where('client_ci','LIKE', "%$filtro%");
        }
    }

    public function scopeFiltrarPlaca($query, $filtro) {
        if ($filtro) {
            return $query->where('vehicle_registration','LIKE',"%$filtro%");
        }
    }

    public function scopeFiltrarEstatus($query, $filtro) {
        if ($filtro) {
            switch ($filtro) {
                case 'vigente':
                    return $query->where('expiring_date','>',Carbon::today())->where('statusu',null);

                case 'vence hoy':
                    return $query->where('expiring_date',Carbon::today())->where('statusu',null);

                case 'vencida':
                    return $query->where('expiring_date','<',Carbon::today())->where('statusu',null);

                case 'anulada':
                    return $query->where('statusu',1);                
            }
        }
    }

    public function scopeFiltrarVendedor($query, $filtro) {
        if($filtro) {
            return $query->where('user_id',$filtro);
        }
    }

    // Relaciones
    public function pagos(){
        return $this->hasMany(Pagos::class);
    }

    public function vehicle(){
    	return $this->belongsTo(Vehicle::class);
    }

    public function user(){
    	//return $this->belongsTo('App\User', 'user_id', 'id')->withTrashed();
        return $this->belongsTo(User::class);// User::class no tien soft delete ->withTrashed();
    }

    /**
     * Este es el plan contratado
     */
    public function price(){                 //aqui     //alla
        #return $this->belongsTo('App\Price', 'price_id', 'id')->withTrashed();
        return $this->belongsTo(Price::class)->withTrashed();
    }

    /**
     * Pago a proveedores asociado a la poliza
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function class(){
        return $this->belongsTo(VehicleClass::class, 'vehicle_class_id')->withTrashed();
    }

    // TODO: simplificar esto a parroquia solamente??
    public function estado(){
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado'); // TODO verificar esto
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id_municipio');
    }

    public function parroquia(){
        return $this->belongsTo(Parroquia::class, 'id_parroquia', 'id_parroquia');
    }

    // Atributos

    /**
     * Obtiene la ganancia para esta poliza para un vendedor
     */
    public function getComisionAttribute()
    {
        return round(($this->total_premium * $this->user->profit_percentage) / 100,2);
    }

     /**
     * Devuelve un arreglo con el tipo de documento primero y el numero de cedula
     * como segundo atributo.
     */
    public function getCedulaArrayAttribute() {
        return explode('-', $this->client_ci, 2);        
    }

    /**
     * Devuelve el nombre completo del comprador
     */
    public function getNombreCompletoAttribute() {
        return $this->client_name_contractor . ' ' . $this->client_lastname_contractor;
    }

    /**
     * Devuelve la direccion completo con estado, municipio, parroquia y direccion
     */
    public function getDireccionCompletaAttribute() {
        return $this->estado->estado . 
            ',' . $this->municipio->municipio . 
            ',' . $this->parroquia->parroquia . 
            ',' . $this->client_address;
    }

    // Metodos "especiales"

    /**
     * Obtiene el siguiente correlativo
     */
    public static function siguienteCorrelativo(): int
    {
        $ultima_poliza = Policy::orderBy('idp','desc')->first();
        return $ultima_poliza->idp + 1;
    }

    public static function insertData($data){
        $value = DB::table('policies')->where('id', $data['id'])->get();
        if($value->count() == 0){
           DB::table('policies')->insert($data);
        }
    }
}
