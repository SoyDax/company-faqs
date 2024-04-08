<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
  
    public function index() :Response
    {
         // todos los registros de la tabla, se asignan a la variable departamentos
        //  $departments = Department::all();
         // se retorna mediante Inertia hacia la vista de la carpeta deptos y el archivo index
         // a la vista se le envian los deptos como parametros o props
        //  return Inertia::render('Admin/Departments/DepartmentIndex', ['departments' => $departments]);

         return Inertia :: render('Admin/Departments/DepartmentIndex',[
            
            'departments' => DepartmentResource::collection(Department::all())
        ]);
    }

    public function create(): Response
    
    {
            //    Autorizar para crear basado en los roles, en postPolicy
            $this->authorize('create', Department::class);
        return Inertia :: render(component:'Admin/Departments/Create');
   
    }


    public function store(CreateDepartmentRequest $request): RedirectResponse
    {
         //    Autorizar para crear basado en los roles, en postPolicy
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
         //    Autorizar para crear basado en los roles, en postPolicy
         $this->authorize('update',$department);    
       return Inertia::render(component: 'Admin/Departments/Edit',props:[
           'department' => new DepartmentResource($department)
       ]);
    }

    public function update(CreateDepartmentRequest $request,  Department $department): RedirectResponse
    {
       //    Autorizar para crear basado en los roles, en postPolicy
       $this->authorize('update',$department);   
        $department->update($request->validated());


        return to_route(route: 'departments.index');
    }
   
    public function destroy(Department $department)
    {
          //    Autorizar para crear basado en los roles, en postPolicy
       $this->authorize('delete',$department);   
       
         $department->delete();

         return back();
    }
}
