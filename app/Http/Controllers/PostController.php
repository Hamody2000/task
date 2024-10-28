<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Auth::user()->posts()->with('tags')->orderBy('pinned', 'desc')->get();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image',
            'pinned' => 'required|boolean',
            'tags' => 'array|exists:tags,id'
        ]);

        $post = Auth::user()->posts()->create($request->only('title', 'body', 'cover_image', 'pinned'));
        $post->tags()->sync($request->tags);

        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return response()->json($post->load('tags'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'cover_image' => 'nullable|image',
            'pinned' => 'sometimes|required|boolean',
            'tags' => 'array|exists:tags,id'
        ]);

        $post->update($request->only('title', 'body', 'cover_image', 'pinned'));
        $post->tags()->sync($request->tags);

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return response()->json(null, 204);
    }

    public function deleted()
    {
        $posts = Auth::user()->posts()->onlyTrashed()->get();
        return response()->json($posts);
    }

    public function restore($id)
    {
        $post = Auth::user()->posts()->onlyTrashed()->findOrFail($id);
        $post->restore();
        return response()->json($post);
    }
}
