<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        // Paginacion mostrar predeterminado 5
        $perPage = $request->input('perPage') ?:5;
        // funcion del buscador search
        $categories = Category::query()
        ->when($request->input('search'), function( $query, $search){
            $query ->where('name','like', "%{$search}%");
        })
        ->paginate($perPage) // Paginacion mostrar mas registros
        ->withQueryString()
        ->through(function ($categories) {
            return new CategoryResource($categories);
        });
        //  estoy utilizando array_merge para combinar el array que se obtiene de compact('departments') con el array 'filters' => $request->only, el prop mantiene la busqueda al cambiar depagina,
        //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
        return Inertia::render('Admin/Categories/CategoryIndex', 
        array_merge(compact('categories'), ['from' => $categories->firstItem(), 
        'filters' => $request->only(['search', 'perPage'])]));
    }
   

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia :: render(component:'Admin/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());
        return to_route(route: 'categories.index');
    }

   
    public function show(Category $category)
    {
        //
    }

 
    public function edit(Category $category): Response
    {
        return Inertia::render(component:'Admin/Categories/Edit', props:[
            'category' => new CategoryResource($category)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());
        return to_route(route: 'categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return back();
    }
}