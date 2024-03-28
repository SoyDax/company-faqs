<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RevokePermissionFromRoleController extends Controller
{
    public function __invoke(Role $role, Permission $permission)
    {
       $role->revokePermissionTo($permission);
         return back();
    }
}
