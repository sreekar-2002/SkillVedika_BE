<?php

namespace App\Http\Controllers;

use App\Models\Seo;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    // GET /api/seo
    public function index()
    {
        // Return all SEO rows (latest first) so the admin UI can list every entry.
        // Previously this returned only the latest item which caused the frontend
        // table to show a single row. Returning the full collection fixes that.
        $seos = Seo::orderBy('id', 'desc')->get();
        return response()->json([
            'data' => $seos
        ]);
    }

    // GET /api/seo/{id}
    public function show($id)
    {
        $seo = Seo::find($id);

        if (!$seo) {
            return response()->json(['message' => 'SEO page not found'], 404);
        }

        return response()->json(['data' => $seo]);
    }

    // POST /api/seo
    public function store(Request $request)
    {
        $request->validate([
            'page' => 'required|string|max:255',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $seo = Seo::create($request->all());

        return response()->json([
            'message' => 'SEO created successfully',
            'data' => $seo
        ], 201);
    }

    // POST /api/seo/{id}
    public function update(Request $request, $id)
    {
        $seo = Seo::find($id);

        if (!$seo) {
            return response()->json(['message' => 'SEO page not found'], 404);
        }

        $seo->update([
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return response()->json([
            'message' => 'SEO updated successfully',
            'data' => $seo
        ]);
    }

    // DELETE /api/seo/{id}
    public function destroy($id)
    {
        $seo = Seo::find($id);

        if (!$seo) {
            return response()->json(['message' => 'SEO page not found'], 404);
        }

        $seo->delete();

        return response()->json([
            'message' => 'SEO deleted successfully'
        ]);
    }
}
