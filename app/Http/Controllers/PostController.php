<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\Department;
use App\Models\Post;
use App\Models\SubCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{



    public function index(Request $request): Response
    {
        // obtienee department_id del usuario autenticado
        $userDepartmentId = auth()->user()->department_id;
    
        $isAdmin = auth()->user()->hasRole('admin') || auth()->user()->hasPermissionTo('Editar departamento');
        
        // Paginacion que este muest5ra predeterminado 5
        $perPage = $request->input('perPage') ?:10;

        // Recoge todos los posts del departamento
        $departmentPostsQuery = Post::query()
        ->when(!$isAdmin, function ($query) use ($userDepartmentId) {
            $query->where('department_id', $userDepartmentId);
        });

        // Obtiene las categorías de esos posts
        $categoryIds = $departmentPostsQuery->get()->pluck('category_id')->unique();
        $categories = Category::whereIn('id', $categoryIds)->get();

        // Función del buscador search
        $postsQuery = $departmentPostsQuery
        ->when($request->input('search'), function( $query, $search){
            $query ->where('title','like', "%{$search}%");
        })
        ->when($request->input('category_id'), function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        });

        // Paginar los posts
        $posts = $postsQuery->paginate($perPage) // Paginacion mostrar mas registros
        ->withQueryString()
        ->through(function ($post) {
            return new PostResource($post);
        });

        //  estoy utilizando array_merge para combinar el array que se obtiene de compact('posts') con el array 'filters' => $request->only, el prop mantiene la busqueda al cambiar depagina,
        //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
        return Inertia::render('Admin/Posts/PostIndex', array_merge(compact('posts', 'categories'), ['from' => $posts->firstItem(), 'filters' => $request->only(['search', 'perPage', 'category_id'])]));
    }
    

    public function create(): Response
    {
          //    Autorizar para crear basado en los roles, en postPolicy
        $this->authorize('create', Post::class);
        return Inertia::render('Admin/Posts/Create',[
            'categories' => CategoryResource::collection(Category::all()),
            'subcategories' => SubCategoryResource::collection(SubCategory::all()),
            'departments' => DepartmentResource::collection(Department::all())
        ]);
        
        
    }

    public function store(CreatePostRequest $request): RedirectResponse
    {
    //    Autorizar para crear basado en los roles, en postPolicy
        $this->authorize('create', Post::class);
        Post::create($request->validated());

        return to_route('posts.index');
    }

    public function show(Post $post): Response
    {
        return Inertia::render('Admin/Posts/Show', [
            'post' => new PostResource($post),
            'categories' => CategoryResource::collection(Category::all()),
            'subcategories' => SubCategoryResource::collection(SubCategory::all()),
            'departments' => DepartmentResource::collection(Department::all())
        ]);
    }


    public function edit(Post $post): Response
    {
        $this->authorize('update', $post);
        // $post->load(['category', 'subCategory']);
      return Inertia::render('Admin/Posts/Edit', [
          'post' => new PostResource($post),
            'categories' => CategoryResource::collection(Category::all()),
            'subcategories' => SubCategoryResource::collection(SubCategory::all()),
            'departments' => DepartmentResource::collection(Department::all())
          
      ]);
    }

    public function update(CreatePostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);
        $post->update($request->validated());

        return back();
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);
        $post->delete();

        return back();
    }
}
