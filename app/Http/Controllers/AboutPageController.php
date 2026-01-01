<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutPageContent;

class AboutPageController extends Controller
{
    public function show()
    {
        $data = AboutPageContent::orderBy('id', 'desc')->first();

        return response()->json([
            'success' => true,
            'data' => $data ?? (object)[]
        ]);
    }

    public function index()
    {
        $data = AboutPageContent::orderBy('id', 'desc')->first();

        return response()->json([
            'success' => true,
            'data' => $data ?? (object)[]
        ]);
    }

    // CREATE new record (POST)
    public function store(Request $request)
    {
        $data = $request->validate([
            'aboutus_image'        => 'nullable|string',
            'aboutus_title'        => 'nullable|array',
            'aboutus_description'  => 'nullable|string',

            'demo_title'           => 'nullable|array',
            'demo_content'         => 'nullable|array',
        ]);

        // Always create new row
        $record = AboutPageContent::create($data);

        return response()->json([
            'success' => true,
            'message' => 'About page content created successfully',
            'data' => $record
        ], 201);
    }

    // UPDATE specific record or latest when id omitted
    public function update(Request $request, $id = null)
    {
        $data = $request->validate([
            'aboutus_image'        => 'nullable|string',
            'aboutus_title'        => 'nullable|array',
            'aboutus_description'  => 'nullable|string',

            'demo_title'           => 'nullable|array',
            'demo_content'         => 'nullable|array',
        ]);

        $record = $id ? AboutPageContent::find($id) : AboutPageContent::orderBy('id', 'desc')->first();

        if (!$record) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $filtered = array_filter($data, function ($v) { return !is_null($v); });

        $record->update($filtered);

        return response()->json([
            'success' => true,
            'message' => 'About page content updated successfully',
            'data' => $record
        ]);
    }

    // DELETE — remove specific or latest AboutPageContent
    public function destroy($id = null)
    {
        $record = $id ? AboutPageContent::find($id) : AboutPageContent::orderBy('id', 'desc')->first();

        if (!$record) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted'
        ]);
    }
}
