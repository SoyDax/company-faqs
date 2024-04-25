<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubCategoryController extends Controller
{
    
    public function index(Request $request): Response
    {
         // Paginacion mostrar predeterminado 5
         $perPage = $request->input('perPage') ?:5;
         // funcion del buscador search
         $subcategories = SubCategory::query()
         ->when($request->input('search'), function( $query, $search){
             $query ->where('name','like', "%{$search}%");
         })
         ->paginate($perPage) // Paginacion mostrar mas registros
         ->withQueryString()
         ->through(function ($subcategory) {
             return new SubCategoryResource($subcategory);
         });
         //  estoy utilizando array_merge para combinar el array que se obtiene de compact('subcategories') con el array 'filters' => $request->only, el prop mantiene la busqueda al cambiar depagina,
         //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
         return Inertia::render('Admin/Subcategories/SubcategoryIndex', 
         array_merge(compact('subcategories'), 
         ['from' => $subcategories->firstItem(), 
         'filters' => $request->only(['search', 'perPage'])]));
             
    }


    public function create(): Response
    {
        return Inertia::render('Admin/Subcategories/Create');
    }

  
    public function store(CreateSubCategoryRequest $request): RedirectResponse
    {
        SubCategory::create($request->validated());
        return to_route(route: 'subcategories.index');
    }

  
    public function show(SubCategory $subcategory)
    {
        //
    }


    public function edit(SubCategory $subcategory): Response
    {
        return Inertia::render(component: 'Admin/Subcategories/Edit', props: [
            'subcategory' => new SubCategoryResource($subcategory)
        ]);
    }

  
    public function update(CreateSubCategoryRequest $request, SubCategory $subcategory): RedirectResponse
    {
        $subcategory->update($request->validated());
        return to_route(route: 'subcategories.index');
    }

   
    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();
        return back();
    }
}
