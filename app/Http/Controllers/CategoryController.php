<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{

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
        //    Autorizar para crear basado en los roles, en categoryPolicy
        $this->authorize('create', Category::class);
        return Inertia :: render(component:'Admin/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request): RedirectResponse
    {
        //    Autorizar para crear basado en los roles, en categoryPolicy
        $this->authorize('create', Category::class);
        Category::create($request->validated());
        return to_route(route: 'categories.index');
    }

   
    public function show(Category $category)
    {
        //
    }

 
    public function edit(Category $category): Response
    {   
        //    Autorizar para crear basado en los roles, en categoryPolicy
        $this->authorize('update', $category);
        return Inertia::render(component:'Admin/Categories/Edit', props:[
            'category' => new CategoryResource($category)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCategoryRequest $request, Category $category): RedirectResponse
    {
        //    Autorizar para crear basado en los roles, en categoryPolicy
        $this->authorize('update', $category);
        $category->update($request->validated());
        return to_route(route: 'categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy(Category $category)
    {
        // Autorizar para crear basado en los roles, en categoryPolicy
        $this->authorize('delete', $category);

        // Buscar a los posts que están asignados a esta categoria
        $posts = Post::where('category_id', $category->id)->get();
        // Si hay posts asignados a esta categoria, guardar el mensaje en una variable y no eliminar la categoria
        if ($posts->count() > 0) {
            $messageError = 'No se puede eliminar esta categoria porque tiene ' . $posts->count() . ' FAQS con esta categoria.';
             // Retornar a la vista con el mensaje
        return Inertia::render('Admin/Categories/CategoryIndex', ['messageError' => $messageError]);
        } else {
            // Eliminar la categoria
            $category->delete();
        }
        return back();
    }

}
