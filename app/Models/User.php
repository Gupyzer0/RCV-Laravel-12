<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mod_id',
        'type',
        'username',
        'name',
        'lastname',
        'ci',
        'email',
        'password',
        'profit_percentage',
        'office_id',
        'phone_number',
        'ncontra',
        'bank_id',
        'bank_account',
        'bank_number',
        'bank_phone',
        'rif_document',
        'fotolocal',
        'fotocarnet',
        'ci_document_ju',
        'islr',
        'rif_pn',
        'direccion_pn',
        'google_maps_url_pn',
        'instagram_pn',
        'facebook_pn',
        'registro_mercantil_pj',
        'cedula_rl_pj',
        'direccion_pj',
        'google_maps_url_pj',
        'telefono_pj',
        'correo_pj',
        'instagram_pj',
        'facebook_pj'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Scopes

    /**
     * Scope que filtra a los usuarios dependiendo del rol del usuario autenticado
     */
    public function scopeFiltrarPorUsuarioAutenticado($query) {

        $user = Auth::user();

        if($user->hasRole('administrador'))
        {
            return $query->where('type', $user->type);
        } 
        elseif($user->hasRole('moderador')) 
        {
            return $query->whereIn('id', $user->usuarios_moderados->pluck('id'));
        }       
    }

    // Relaciones
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // public function office()
    // {
    //     return $this->belongsTo(Office::class, 'office_id', 'id')->withTrashed();
    // }

    // Moderador (supervisor) asignado
    public function moderator()
    {
        return $this->belongsTo(User::class, 'mod_id', 'id')->withTrashed();
    }

    // Usuarios moderados
    public function usuarios_moderados()
    {
        return $this->hasMany(User::class, 'mod_id', 'id')->withTrashed();
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id')->withTrashed();
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class, 'causer_id', 'id')->withTrashed();
    }

    public function activePolicies()
    {
        return $this->hasMany(Policy::class, 'user_id', 'id')->where('status', 1)->where('type', $this->type);
    }

    // Atributos

    /**
     * Obtiene el nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return Str::title($this->name . ' ' . $this->lastname);
    }

    // Helpers

    /**
     * Ultimo pago recibido
     */
    public function ultimo_pago_recibido()
    {
        if($this->payments->isNotEmpty())
        {
            return $this->payments->sortByDesc('created_at')->first()->created_at;
        }
    }

    /**
     * Polizas vendidas sin pagar
     */
    public function polizas_vendidas_sin_pagar()
    {
        return $this->policies->where('status',0);
    }

    /**
     * Polizas reportadas sin pagar
     */
    public function polizas_reportadas_sin_pagar()
    {
        return $this->policies->where('status',0)->where('report',1);
    }

    /**
     * Polizas Anuladas
     */
    public function polizas_anuladas()
    {
        return $this->policies->where('statusu',1);
    }

    /**
     * Total de las polizas pendientes sin pagar
     */
    public function total_polizas_sin_pagar()
    {
        return $this->policies->where('status',0)->where('statusu',null)->sum('total_premium');
    }

    /**
     * Total a recibir por las polizas vendidas por este usuario
     */
    public function total_a_recibir()
    {
        return User::profit_percentage($this->total_polizas_sin_pagar(), $this->profit_percentage);
    }

    /**
     * Comision de las polizas sin pagar
     */
    public function comision_polizas_sin_pagar()
    {
        return $this->total_polizas_sin_pagar() - $this->total_a_recibir();
    }

    /**
     * Calculo de comisión ... 
     */
    public static function profit_percentage($value1, $value3)
    {
        $suma = ($value3 * $value1) / 100;
        $result = $value1 - $suma;
        return $result;
    }
}
