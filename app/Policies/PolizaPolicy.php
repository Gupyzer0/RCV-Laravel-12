<?php

namespace App\Policies;

use App\Models\Policy;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Política para las polizas
 */
class PolizaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Policy $policy): bool
    {   
        // Si es un administrador buscamos si el policy tiene el mismo "type" que el admin
        if($user->hasRole('administrador')) {
            return $user->type === $policy->type;
        }

        // Si es moderador buscamos que la poliza sea de uno de los usuarios moderadors por este
        if($user->hasRole('moderador')) {
            return $user->usuarios_moderados->pluck('id')->contains($policy->user->id);
        }

        // Si es un usuario buscamos que la poliza le pertenezca
        if($user->hasRole('usuario')) {
            return $policy->user->id === $user->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // El unico que "crea" polizas seria el usuario (vendedor)
        return $user->hasRole('usuario');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Policy $policy): bool
    {

        // Solo el administrador edita aunque es probable que se le de el permiso
        // a usuarios determinados luego...
        return $user->hasRole('administrador');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Policy $policy): bool
    {
        // Al parecer solo el administrador puede eliminar polizas de su mismo "type"
        if($user->hasRole('administrador')) {
            return $user->type === $policy->type;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Policy $policy): bool
    {
        // Al parecer solo el administrador puede eliminar polizas de su mismo "type"
        if($user->hasRole('administrador')) {
            return $user->type === $policy->type;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Policy $policy): bool
    {
        // Al parecer solo el administrador puede eliminar polizas de su mismo "type"
        if($user->hasRole('administrador')) {
            return $user->type === $policy->type;
        }
        return false;
    }
}
