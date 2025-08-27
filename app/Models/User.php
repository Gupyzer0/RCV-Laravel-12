<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id')->withTrashed();
    }

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

    public function getNombreCompletoAttribute()
    {
        return Str::title($this->name . ' ' . $this->lastname);
    }
}
