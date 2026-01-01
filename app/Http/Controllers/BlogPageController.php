<?php

namespace App\Http\Controllers;

use App\Models\BlogPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogPageController extends Controller
{
    // GET /api/blog-page
    public function index()
    {
        try {
            $content = BlogPageContent::orderBy('id', 'desc')->first();

            // If empty, return empty object so frontend can bind to empty form
            if (!$content) {
                return response()->json([
                    'data' => new \stdClass()
                ], 200);
            }

            return response()->json(['data' => $content], 200);
        } catch (\Throwable $e) {
            // If table doesn't exist, return empty data so frontend can still work
            Log::warning("BlogPageContent table error: " . $e->getMessage());
            return response()->json([
                'data' => new \stdClass()
            ], 200);
        }
    }

    // POST /api/blog-page/update
    public function update(Request $request)
    {
        try {
            // Always create a new record instead of updating existing one
            try {
                $content = new BlogPageContent();
            } catch (\Throwable $dbError) {
                // If table doesn't exist, just return success without saving
                Log::warning("BlogPageContent table doesn't exist or database error: " . $dbError->getMessage());
                return response()->json([
                    'message' => 'Blog page received (database not ready, data not persisted)',
                    'data' => []
                ], 200);
            }

            // Parse JSON fields from frontend
            if ($request->has('hero_title') && is_string($request->hero_title)) {
                $content->hero_title = $this->parseJsonField($request->hero_title);
            } elseif ($request->has('hero_title')) {
                $content->hero_title = $request->hero_title;
            }

            if ($request->has('demo_title') && is_string($request->demo_title)) {
                $content->demo_title = $this->parseJsonField($request->demo_title);
            } elseif ($request->has('demo_title')) {
                $content->demo_title = $request->demo_title;
            }

            if ($request->has('demo_points') && is_string($request->demo_points)) {
                $content->demo_points = $this->parseJsonField($request->demo_points);
            } elseif ($request->has('demo_points')) {
                $content->demo_points = $request->demo_points;
            }

            // Handle hero_image as string URL (from Cloudinary frontend upload)
            if ($request->filled('hero_image') && is_string($request->hero_image)) {
                $content->hero_image = $request->hero_image;
            }

            // Simple string fields
            $content->hero_description = $request->hero_description ?? null;
            $content->sidebar_name = $request->sidebar_name ?? null;
            $content->demo_subtitle = $request->demo_subtitle ?? null;

            try {
                $content->save();

                // Refresh the model to ensure we return the latest data from database
                $content->refresh();
            } catch (\Throwable $saveError) {
                Log::warning("Failed to save BlogPageContent: " . $saveError->getMessage());
                // Still return success so frontend doesn't show error
                return response()->json([
                    'message' => 'Blog page received (save failed, data not persisted)',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Blog page created successfully',
                'data' => $content
            ], 200);
        } catch (\Throwable $e) {
            Log::error("BlogPage UPDATE ERROR: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST — Insert a NEW row
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'hero_title'       => 'nullable|array',
                'hero_description' => 'nullable|string',
                'hero_image'       => 'nullable|string',

                'sidebar_name'     => 'nullable|string',

                'demo_title'       => 'nullable|array',
                'demo_subtitle'    => 'nullable|string',
                'demo_points'      => 'nullable|array',
            ]);

            $record = BlogPageContent::create($data);

            return response()->json([
                'message' => 'Blog page content created successfully',
                'data' => $record
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE — remove latest or specific blog page content
    public function destroy($id = null)
    {
        try {
            $record = $id ? BlogPageContent::find($id) : BlogPageContent::orderBy('id', 'desc')->first();

            if (!$record) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $record->delete();

            return response()->json(['message' => 'Deleted', 'data' => $record]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to safely parse JSON strings
     */
    private function parseJsonField($value)
    {
        if (is_string($value)) {
            try {
                return json_decode($value, true);
            } catch (\Throwable $e) {
                return $value;
            }
        }
        return $value;
    }
}
