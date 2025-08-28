<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Este scope define que polizas son visibles para cada tipo de usuario
 */
class PolizasScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::hasUser()) // De esta forma podemos usar tinker . . .
        {
            $user = Auth::user();
            // Si es un administrador buscamos si el policy tiene el mismo "type" que el admin
            if($user->hasRole('administrador')) {
                $builder->where('type',$user->type);
            }

            // Si es moderador buscamos que la poliza sea de uno de los usuarios moderadors por este
            if($user->hasRole('moderador')) {
                $builder->whereIn('user_id', $user->usuarios_moderados->pluck('id'));
            }

            // Si es un usuario buscamos que la poliza le pertenezca
            if($user->hasRole('usuario')) {
                $builder->where('user_id', $user->id);
            }
        }
    }
}
