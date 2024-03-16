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
        return Inertia :: render(component:'Admin/Departments/Create');
   
    }


    public function store(CreateDepartmentRequest $request): RedirectResponse
    {
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
       return Inertia::render(component: 'Admin/Departments/Edit',props:[
           'department' => new DepartmentResource($department)
       ]);
    }

    public function update(CreateDepartmentRequest $request,  Department $department): RedirectResponse
    {
        $department->update($request->validated());


        return to_route(route: 'departments.index');
    }
   
    public function destroy(Department $department)
    {
       
         $department->delete();

         return back();
    }
}
