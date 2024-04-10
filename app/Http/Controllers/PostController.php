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
    public function index(): Response
    {
        $posts = Post::paginate(3)->through(function ($post) {
            return new PostResource($post);
        });
    
        return Inertia::render('Admin/Posts/PostIndex', compact('posts'));
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
