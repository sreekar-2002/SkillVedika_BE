<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        return Category::orderBy('id', 'DESC')->get(['id', 'name']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        return Category::create([
            'name' => $request->name
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $category = Category::findOrFail($id);

        $category->update(['name' => $request->name]);

        return response()->json(['message' => 'Category updated successfully']);
    }

    public function destroy($id)
    {
        Category::destroy($id);

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
