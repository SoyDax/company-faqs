<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentResource;
use App\Models\Department;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
    }
}
