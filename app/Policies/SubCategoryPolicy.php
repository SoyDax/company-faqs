<?php

namespace App\Policies;

use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubCategoryPolicy
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
    public function view(User $user, SubCategory $subCategory): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         // crear policies, revisar documentacion Laravel, Segurity, Authorization, Policies
        // Si el usuario tiene uno de estos roles, la función devuelve `true`. Si no, devuelve `false`. 
        // return $user -> hasRole(['admin' , 'moderador', 'escritor'] ) ? true : false;

        // Si el usuario tiene uno de estos permisos, la función devuelve `true`. Si no, devuelve `false`.
        return $user -> hasPermissionTo ( 'Crear subcategoria' ) ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubCategory $subCategory): bool
    {
          // Si el usuario tiene uno de estos permisos, la función devuelve `true`. Si no, devuelve `false`.
          return $user -> hasPermissionTo ( 'Editar subcategoria' ) ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubCategory $subCategory): bool
    {
         // Si el usuario tiene uno de estos permisos, la función devuelve `true`. Si no, devuelve `false`.
         return $user -> hasPermissionTo ( 'Eliminar subcategoria' ) ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubCategory $subCategory): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubCategory $subCategory): bool
    {
        //
    }
}
