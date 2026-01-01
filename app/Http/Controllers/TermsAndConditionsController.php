<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TermsAndConditions;

class TermsAndConditionsController extends Controller
{
    // GET /api/terms - Get latest terms record
    public function show()
    {
        $record = TermsAndConditions::orderBy('id', 'desc')->first();
        return response()->json([
            "success" => true,
            "data" => $record ?? null
        ]);
    }

    // GET /api/terms/all - Get all terms records
    public function index()
    {
        return response()->json([
            "success" => true,
            "data" => TermsAndConditions::orderBy('id', 'desc')->get()
        ]);
    }

    // POST /api/terms - Always create a new record
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable',
        ]);

        // Always create a new record instead of updating existing one
        $terms = new TermsAndConditions();
        $terms->title = $request->title;
        $terms->content = $request->content;
        $terms->last_updated_on = now();
        $terms->save();

        // Refresh the model to ensure we return the latest data from database
        $terms->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Terms & Conditions created successfully',
            'data' => $terms,
        ], 201);
    }

    // PUT /api/terms/{id} - Update specific record
    public function update(Request $request, $id = null)
    {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable',
        ]);

        // If no ID provided, update the latest record
        if (!$id) {
            $terms = TermsAndConditions::orderBy('id', 'desc')->first();
            if (!$terms) {
                return response()->json(['success' => false, 'message' => 'No terms found'], 404);
            }
        } else {
            $terms = TermsAndConditions::findOrFail($id);
        }

        $terms->title = $request->title;
        $terms->content = $request->content;
        $terms->last_updated_on = now();
        $terms->save();

        return response()->json([
            'success' => true,
            'message' => 'Terms & Conditions updated successfully',
            'data' => $terms,
        ]);
    }

    // PATCH /api/terms/{id} - Partial update
    public function patch(Request $request, $id = null)
    {
        // If no ID provided, update the latest record
        if (!$id) {
            $terms = TermsAndConditions::orderBy('id', 'desc')->first();
            if (!$terms) {
                return response()->json(['success' => false, 'message' => 'No terms found'], 404);
            }
        } else {
            $terms = TermsAndConditions::findOrFail($id);
        }

        if ($request->has('title')) {
            $terms->title = $request->title;
        }
        if ($request->has('content')) {
            $terms->content = $request->content;
        }

        $terms->last_updated_on = now();
        $terms->save();

        return response()->json([
            'success' => true,
            'message' => 'Terms & Conditions updated successfully',
            'data' => $terms,
        ]);
    }

    // DELETE /api/terms/{id} - Delete specific record
    public function destroy($id)
    {
        $terms = TermsAndConditions::findOrFail($id);
        $terms->delete();

        return response()->json([
            'success' => true,
            'message' => 'Terms & Conditions deleted successfully',
        ]);
    }
}
