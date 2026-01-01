<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnJobSupportPageContent;
use App\Http\Resources\OnJobSupportResource;

class OnJobSupportContentController extends Controller
{
    // POST create new content row
    public function store(Request $request)
    {
        try {
            $content = OnJobSupportPageContent::create($request->all());

            return new OnJobSupportResource($content);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // GET latest content
    public function index()
    {
        try {
            $latest = OnJobSupportPageContent::orderBy('id', 'desc')->first();

            if (!$latest) {
                return response()->json(['message' => 'No content available'], 200);
            }

            return new OnJobSupportResource($latest);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch On Job Support Content
     */
    public function show()
    {
        return $this->index();
    }

    // PUT/PATCH — Update the LATEST row (or specific if id provided)
    public function update(Request $request, $id = null)
    {
        try {
            $data = $request->validate([
                'hero_title'     => 'nullable|array',
                'realtime_title' => 'nullable|array',
                'who_title'      => 'nullable|array',
                'who_cards'      => 'nullable|array',
                'how_title'      => 'nullable|array',
                'how_points'     => 'nullable|array',
                'process_title'  => 'nullable|array',
                'process_points' => 'nullable|array',
                'why_title'      => 'nullable|array',
                'why_points'     => 'nullable|array',
                'ready_title'    => 'nullable|array',
                'demo_title'     => 'nullable|array',
                'demo_points'    => 'nullable|array',
                'meta_title'        => 'nullable',
                'meta_description'  => 'nullable',
                'meta_keywords'     => 'nullable',
            ]);

            $record = $id ? OnJobSupportPageContent::find($id) : OnJobSupportPageContent::orderBy('id', 'desc')->first();

            if (!$record) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $filtered = array_filter($data, function ($v) {
                return !is_null($v);
            });

            $record->update($filtered);

            return response()->json([
                'success' => true,
                'message' => 'Updated',
                'data' => $record
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE — remove specific or latest record
    public function destroy($id = null)
    {
        try {
            $record = $id ? OnJobSupportPageContent::find($id) : OnJobSupportPageContent::orderBy('id', 'desc')->first();

            if (!$record) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $record->delete();

            return response()->json([
                'success' => true,
                'message' => 'Deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
