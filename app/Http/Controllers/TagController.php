<?php

namespace App\Http\Controllers;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // List all tags
    public function index()
    {
        return Tag::all();
    }

    // Store a new tag
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:tags|max:255',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
        ]);

        return response()->json($tag, 201);
    }

    // Show a specific tag
    public function show(Tag $tag)
    {
        return $tag;
    }

    // Update a tag
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|unique:tags,name,' . $tag->id . '|max:255',
        ]);

        $tag->update([
            'name' => $request->name,
        ]);

        return response()->json($tag, 200);
    }

    // Delete a tag
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(null, 204);
    }
}
