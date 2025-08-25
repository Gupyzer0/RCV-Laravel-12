<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Policy2 extends Model
{
	use SoftDeletes;//, LogsActivity;

    protected static $logName = "Solicitud_Poliza";
    protected $table = "policies2";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'idp', 'type', 'user_id', 'admin_id', 'price_id','trailer', 'vehicle_id', 'id_estado',
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

    public function pagos(){
        return $this->hasMany(Pagos::class);
    }

    public function vehicle(){
    	return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function price()
    {
        return $this->belongsTo(Price::class, 'price_id', 'id')->withTrashed();
    }

    public function class(){
        return $this->belongsTo(VehicleClass::class, 'vehicle_class_id')->withTrashed();
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id_municipio');
    }

    public function parroquia(){
        return $this->belongsTo(Parroquia::class, 'id_parroquia', 'id_parroquia');
    }

    public static function procesarPoliza(Policy2 $policy2, $id_bank, $amount, $reference_number)
    {
        // Obtener tasas de cambio
        $euro = ForeignUnit::first()->foreign_reference;
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        // Procesar la póliza
        //$policy2 = Policy2::findOrFail($id);
        $policy = new Policy();
        
        $data = $policy2->toArray();
        $data['idp'] = Policy::siguienteCorrelativo();
        unset($data['id']);
        
        $policy->fill($data);
        $policy->expiring_date = Carbon::now()->addYear();
        $policy->foreign = $euro;
        $dolar = $dolar;
        $policy->save();

        // Registrar el pago
        Rreport::create([
            'id_policy' => $policy->id,
            'user_id' => Auth::id(),
            'id_bank' => $id_bank,
            'amount' => str_replace(',', '.', $amount),
            'reference_number' => $reference_number,
        ]);

        // Eliminar la cotización original
        if($policy->idp == $policy2->id) {
            $policy2->delete();
        }

        return $policy; 
    }

    public static function insertData($data){
        $value = DB::table('policies')->where('id', $data['id'])->get();
        if($value->count() == 0){
           DB::table('policies')->insert($data);
        }
    }
}
