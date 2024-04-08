<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
       
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
      
// Si el usuario tiene uno de estos roles, la función devuelve `true`. Si no, devuelve `false`. 
        // return $user -> hasRole(['admin' , 'moderador', 'escritor'] ) ? true : false;

        // Si el usuario tiene uno de estos permisos, la función devuelve `true`. Si no, devuelve `false`.
        return $user -> hasPermissionTo ( 'Crear FAQ' ) ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user -> hasPermissionTo ( 'Editar FAQ' ) ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user -> hasPermissionTo ( 'Eliminar FAQ' ) ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        //
    }
}
