<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Moderator extends Authenticatable
{
    use Notifiable; //, LogsActivity;
    use SoftDeletes;
    protected static $logName = "Supervisor";
    protected $guard= 'moderator';
    protected $fillable = [
        'id','type', 'name', 'email', 'password','ci','phone_number'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Usuarios bajo la supervision de este moderador (supervisor).
     * TODO: esto podria cambiar a ser igual que el query en el controlador
     * donde solo se toman en cuenta los usuarios que tienen al menos una
     * poliza con status == 0. Pero de momento usaremos una relación directamente
     * ya que trae menos problemas . . .
     */
    public function supervisados()
    {
        return $this->hasMany(User::class, 'mod_id');
    }

    public function logs(){
        return $this->hasMany(ActivityLog::class, 'causer_id', 'id');
    }
}
