<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
  
    public function index(Request $request): Response
    {

        // Paginacion mostrar predeterminado 5
        $perPage = $request->input('perPage') ?:5;
        // funcion del buscador search
        $departments = Department::query()
        ->when($request->input('search'), function( $query, $search){
            $query ->where('name','like', "%{$search}%");
        })
        ->paginate($perPage) // Paginacion mostrar mas registros
        ->withQueryString()
        ->through(function ($department) {
            return new DepartmentResource($department);
        });
        //  estoy utilizando array_merge para combinar el array que se obtiene de compact('departments') con el array 'filters' => $request->only, el prop mantiene la busqueda al cambiar depagina,
        //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
        return Inertia::render('Admin/Departments/DepartmentIndex', array_merge(compact('departments'), ['from' => $departments->firstItem(), 'filters' => $request->only(['search', 'perPage'])]));
            
    }

    public function create(): Response
    
    {
            //    Autorizar para crear basado en los roles, en departmentPolicy
            $this->authorize('create', Department::class);
        return Inertia :: render(component:'Admin/Departments/Create');
   
    }


    public function store(CreateDepartmentRequest $request): RedirectResponse
    {
         //    Autorizar para crear basado en los roles, en departmentPolicy
         $this->authorize('create', Department::class);    
        Department::create($request->validated());
        return to_route(route: 'departments.index');
    }


    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department): Response

    {
         //    Autorizar para crear basado en los roles, en departmentPolicy
         $this->authorize('update',$department);    
       return Inertia::render(component: 'Admin/Departments/Edit',props:[
           'department' => new DepartmentResource($department)
       ]);
    }

    public function update(CreateDepartmentRequest $request,  Department $department): RedirectResponse
    {
       //    Autorizar para crear basado en los roles, en departmentPolicy
       $this->authorize('update',$department);   
        $department->update($request->validated());


        return to_route(route: 'departments.index');
    }
   
    
    
    
    
    
    public function destroy(Department $department)
    {
        // Autorizar para eliminar basado en los roles, en departmentPolicy
        $this->authorize('delete', $department);   

        // Buscar a los usuarios que están asignados a este departamento
        $users = User::where('department_id', $department->id)->get();

        // Buscar a los posts que están asignados a este departamento
        $posts = Post::where('department_id', $department->id)->get();

        $messageError = '';

        // Si hay usuarios asignados a este departamento, agregar al mensaje
        if ($users->count() > 0) {
            $messageError .= 'No se puede eliminar este departamento porque tiene ' . $users->count() . ' usuarios con este departamento. ';
        } 

        // Si hay posts asignados a este departamento, agregar al mensaje
        if ($posts->count() > 0) {
            $messageError .= 'No se puede eliminar este departamento porque tiene ' . $posts->count() . ' FAQS con este departamento. ';
        }

        // Si hay un mensaje de error, retornar a la vista con el mensaje
        if ($messageError != '') {
            return Inertia::render('Admin/Departments/DepartmentIndex', ['messageError' => $messageError]);
        } else {
            // Eliminar la categoria
            $department->delete();
        }

        return back();
    }
    
    
}
