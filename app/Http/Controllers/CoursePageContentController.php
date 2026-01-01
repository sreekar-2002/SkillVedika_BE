<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoursePageContent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CoursePageContentController extends Controller
{
    // CORS headers - should be configured in middleware for production
    private const CORS_ORIGIN = '*';
    private const CORS_METHODS = 'GET, POST, OPTIONS';
    private const CORS_HEADERS = 'Content-Type';

    /**
     * Add CORS headers to response
     */
    private function addCorsHeaders($response)
    {
        return $response
            ->header('Access-Control-Allow-Origin', self::CORS_ORIGIN)
            ->header('Access-Control-Allow-Methods', self::CORS_METHODS)
            ->header('Access-Control-Allow-Headers', self::CORS_HEADERS);
    }

    public function index()
    {
        $content = CoursePageContent::orderBy('id', 'desc')->first();

        return $this->addCorsHeaders(response()->json($content ?? []));
    }

    // Keep get() for backward compatibility
    public function get()
    {
        return $this->index();
    }

    // POST — create a new row
    public function store(Request $request)
    {
        $data = $request->validate([
            'heading' => 'nullable|string',
            'subheading' => 'nullable|string',
            'sidebar_heading' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable',
        ]);

        $content = CoursePageContent::create($data);

        return $this->addCorsHeaders(response()->json([
            'message' => 'Course page content created',
            'data' => $content
        ], 201));
    }

    // PUT/PATCH — update latest or specific record
    public function update(Request $request, $id = null)
    {
        // Validate input and return JSON errors instead of redirecting
        $validator = Validator::make($request->all(), [
            'heading' => 'required|string',
            'subheading' => 'required|string',
            'sidebar_heading' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422));
        }

        $validated = $validator->validated();

        try {
            $content = CoursePageContent::orderBy('id', 'desc')->first();

            if (!$content) {
                $content = CoursePageContent::create($validated);
            } else {
                $content->update($validated);
            }

            return $this->addCorsHeaders(response()->json([
                'success' => true,
                'message' => 'Course page updated successfully',
                'data' => $content,
            ]));
        } catch (\Exception $e) {
            // Return JSON error for any unexpected exception
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500));
        }
    }

    // DELETE — remove specific or latest record
    public function destroy($id = null)
    {
        $record = $id ? CoursePageContent::find($id) : CoursePageContent::orderBy('id', 'desc')->first();

        if (!$record) {
            return $this->addCorsHeaders(response()->json(['message' => 'Not found'], 404));
        }

        $record->delete();

        return $this->addCorsHeaders(response()->json(['message' => 'Deleted'], 200));
    }
}
