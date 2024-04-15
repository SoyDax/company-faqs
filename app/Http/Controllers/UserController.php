<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentResource;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
            // Paginacion mostrar predeterminado 5
            $perPage = $request->input('perPage') ?:5;
            // funcion del buscador search
            $users = User::query()
            ->when($request->input('search'), function( $query, $search){
                $query ->where('name','like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate($perPage) // Paginacion mostrar mas registros
            ->withQueryString()
            ->through(function ($user) {
                return new UserResource($user);
            });
       
    //  estoy utilizando array_merge para combinar el array que se obtiene de compact('users') con el array 'filters' => $request->only, el prop mantiene la busqueda al cambiar depagina,
    //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
    return Inertia::render('Admin/Users/UserIndex', array_merge(compact('users'), ['from' => $users->firstItem(), 'filters' => $request->only(['search', 'perPage'])]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia :: render('Admin/Users/Create', [
            'roles' => RoleResource::collection(Role::all()),
            'permissions' => PermissionResource::collection(Permission::all()),
            'departments' => DepartmentResource::collection(Department::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'department_id' => ['required', 'integer'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'password' => Hash::make($request->password),
        ]);
        $user->syncRoles($request->input('roles.*.name'));
        $user->syncPermissions($request->input('permissions.*.name'));
        return to_route('users.index');
    }

    public function edit(User $user): Response
    {
        $user->load(['roles', 'permissions' , 'department']);
        return Inertia::render( 'Admin/Users/Edit', props: [
            'user' => new UserResource($user),
            'departments' => DepartmentResource::collection(Department::all()),
            'roles' => RoleResource::collection(Role::all()),
            'permissions' => PermissionResource::collection(Permission::all()),
        ]);
    }
  

    public function update(Request $request,User $user): RedirectResponse
    {   
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|'. Rule::unique("users" , "email")->ignore($user),
            'department_id' => ['sometimes', 'integer'],
            'roles' => ['sometimes', 'array'],
            'permissions' => ['sometimes', 'array'],
         
        ]);
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
    
        if ($request->has('department_id')) {
            $updateData['department_id'] = $request->department_id;
        }
    
        $user->update($updateData);
    

        $user->syncRoles($request->input('roles.*.name'));
        $user->syncPermissions($request->input('permissions.*.name'));
       


        return back();
    }
 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return to_route('users.index');
    }
    
}
