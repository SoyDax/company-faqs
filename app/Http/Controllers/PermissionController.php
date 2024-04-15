<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : Response
    {
         // Paginacion mostrar predeterminado 5
         $perPage = $request->input('perPage') ?:5;
         // funcion del buscador search
         $permissions = Permission::query()
         ->when($request->input('search'), function( $query, $search){
             $query ->where('name','like', "%{$search}%");
         })
         ->paginate($perPage) // Paginacion mostrar mas registros
         ->withQueryString()
         ->through(function ($permission) {
             return new PermissionResource($permission);
         });
       
        //  estoy utilizando array_merge para combinar el array que se obtiene de compact('permissions') con el array 'filters' => $request->only, el prop mantiene la busqueda al cambiar depagina,
        //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
        return Inertia::render('Admin/Permissions/PermissionIndex', array_merge(compact('permissions'), ['from' => $permissions->firstItem(), 'filters' => $request->only(['search', 'perPage'])]));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia :: render(component:'Admin/Permissions/Create');
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePermissionRequest $request): RedirectResponse
    {
        Permission::create($request->validated());

        return to_route(route: 'permissions.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): Response
    {
        return Inertia::render(component: 'Admin/Permissions/Edit',props:[
            'permission' => new PermissionResource($permission)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $permission->update($request->validated());

        return to_route(route: 'permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return back();
    }
}
