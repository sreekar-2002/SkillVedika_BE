<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // GET /categories
    public function index()
    {
        return response()->json(Category::orderBy('id', 'DESC')->get());
    }

    // POST /categories
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    // PUT /categories/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'message' => 'Category updated',
            'category' => $category
        ]);
    }

    // DELETE /categories/{id}
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Category deleted'
        ]);
    }
}
