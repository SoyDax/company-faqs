<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : Response
    {
          // Paginacion mostrar predeterminado 5
          $perPage = $request->input('perPage') ?:5;
          // funcion del buscador search
          $roles = Role::query()
          ->when($request->input('search'), function( $query, $search){
              $query ->where('name','like', "%{$search}%");
          })
          ->paginate($perPage) // Paginacion mostrar mas registros
          ->withQueryString()
          ->through(function ($role) {
              return new RoleResource($role);
          });
      
    //  estoy utilizando array_merge para combinar el array que se obtiene de compact('roles') con el array 'filters' => $request->only, el prop mantiene la busqueda al cambiar depagina,
    //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
    return Inertia::render('Admin/Roles/RoleIndex', array_merge(compact('roles'), ['from' => $roles->firstItem(), 'filters' => $request->only(['search', 'perPage'])]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia :: render(component:'Admin/Roles/Create', 
            props: [ 'permissions' => PermissionResource::collection(Permission::all())
            ]);
    }
  
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request)
    {
        $role = Role::create(['name' => $request->name]);
        if ($request->has('permissions')){
            $role->syncPermissions($request->input('permissions.*.name'));
        }

        return to_route(route: 'roles.index');
    }

    public function edit(string $id)
    {
        $role = Role::findById($id);
        $role->load('permissions');

        return Inertia::render(component: 'Admin/Roles/Edit', props: [
            'role' => new RoleResource($role),
            'permissions' => PermissionResource::collection(Permission::all())
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRoleRequest $request, string $id)
    {
        $role = Role::findById($id);
        $role->update([
            'name' => $request->name
        ]);
        $role->syncPermissions($request->input('permissions.*.name'));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findById($id);
        $role->delete();

        return back();
    }
}
