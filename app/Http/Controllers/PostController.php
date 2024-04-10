<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
         // Paginacion mostrar predeterminado 5
        $perPage = $request->input('perPage') ?:5;
        // funcion del buscador search
        $posts = Post::query()
        ->when($request->input('search'), function( $query, $search){
            $query ->where('title','like', "%{$search}%");
        })
        ->paginate($perPage) // Paginacion mostrar mas registros
        ->withQueryString()
        ->through(function ($post) {
            return new PostResource($post);
        });
    //  estoy utilizando array_merge para combinar el array que se obtiene de compact('posts') con el array 'filters' => $request->only, el prop mantiene la busqueda al cambiar depagina,
    //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
    return Inertia::render('Admin/Posts/PostIndex', array_merge(compact('posts'), ['from' => $posts->firstItem(), 'filters' => $request->only(['search', 'perPage'])]));
    }
    public function create(): Response
    {
          //    Autorizar para crear basado en los roles, en postPolicy
        $this->authorize('create', Post::class);
        return Inertia::render('Admin/Posts/Create');
    }

    public function store(CreatePostRequest $request): RedirectResponse
    {
    //    Autorizar para crear basado en los roles, en postPolicy
        $this->authorize('create', Post::class);
        Post::create($request->validated());

        return to_route('posts.index');
    }

    public function edit(Post $post): Response
    {
        $this->authorize('update', $post);
      return Inertia::render('Admin/Posts/Edit', [
          'post' => new PostResource($post)
      ]);
    }

    public function update(CreatePostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);
        $post->update($request->validated());

        return to_route('posts.index');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);
        $post->delete();

        return back();
    }
}
