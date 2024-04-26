<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\Post;
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
        //    Autorizar para crear basado en los roles, en SubCategoryPolicy
        $this->authorize('create', SubCategory::class);
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
        //    Autorizar para editar basado en los roles, en SubCategoryPolicy
        $this->authorize('update', $subcategory);
        return Inertia::render(component: 'Admin/Subcategories/Edit', props: [
            'subcategory' => new SubCategoryResource($subcategory)
        ]);
    }

  
    public function update(CreateSubCategoryRequest $request, SubCategory $subcategory): RedirectResponse
    {
        //    Autorizar para editar basado en los roles, en SubCategoryPolicy
        $this->authorize('update', $subcategory);
        $subcategory->update($request->validated());
        return to_route(route: 'subcategories.index');
    }

   
    public function destroy(SubCategory $subcategory)
    {
        //    Autorizar para eliminar basado en los roles, en SubCategoryPolicy
        $this->authorize('delete', $subcategory);
       
            // Buscar a los usuarios que están asignados a este departamento
            $posts = Post::where('sub_category_id', $subcategory->id)->get();

            // Si hay posts asignados a esta categoria, guardar el mensaje en una variable y no eliminar la categoria
        if ($posts->count() > 0) {
            $messageError = 'No se puede eliminar esta Subcategoria porque tiene ' . $posts->count() . ' FAQS con esta Subcategoria.';
             // Retornar a la vista con el mensaje
        return Inertia::render('Admin/Subcategories/SubcategoryIndex', ['messageError' => $messageError]);
        } else {
            // Eliminar la categoria
            $subcategory->delete();
        }
        return back();
    }
}
