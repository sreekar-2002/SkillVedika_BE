<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PopularTag;

class PopularTagController extends Controller
{
    // GET /popular-tags
    public function index()
    {
        return response()->json(PopularTag::orderBy('id', 'DESC')->get());
    }

    // POST /popular-tags
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'usage_count' => 'nullable|integer|min:0'
        ]);

        $tag = PopularTag::create([
            'name' => $request->name,
            'description' => $request->description,
            'usage_count' => $request->usage_count ?? 0
        ]);

        return response()->json([
            'message' => 'Popular Tag created successfully',
            'tag' => $tag
        ], 201);
    }

    // PUT /popular-tags/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'usage_count' => 'nullable|integer|min:0'
        ]);

        $tag = PopularTag::findOrFail($id);
        $tag->name = $request->name;
        $tag->description = $request->description;
        if ($request->has('usage_count')) {
            $tag->usage_count = $request->usage_count;
        }
        $tag->save();

        return response()->json([
            'message' => 'Popular Tag updated',
            'tag' => $tag
        ]);
    }

    // DELETE /popular-tags/{id}
    public function destroy($id)
    {
        $tag = PopularTag::findOrFail($id);
        $tag->delete();

        return response()->json([
            'message' => 'Popular Tag deleted'
        ]);
    }
}
