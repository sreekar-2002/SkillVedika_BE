<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CorporateTrainingPageContent;

class CorporateTrainingController extends Controller
{
    // GET: return latest row
    public function index()
    {
        try {
            $content = CorporateTrainingPageContent::orderBy('id', 'desc')->first();

            if (!$content) {
                return response()->json([], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $content
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show()
    {
        return $this->index();
    }

    // Optional: update latest row (if you want update endpoint)
    public function updateLatest(Request $request)
    {
        try {
            $content = CorporateTrainingPageContent::orderBy('id', 'desc')->first();
            if (!$content) {
                return response()->json(['message' => 'No content found'], 404);
            }

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'hero_title' => 'nullable|array',
                'hero_subheading' => 'nullable|string',
                'hero_button_text' => 'nullable|string',
                'hero_button_link' => 'nullable|string',
                'hero_image' => 'nullable|string',
                'empower_title' => 'nullable|array',
                'empower_description' => 'nullable|string',
                'empower_image' => 'nullable|string',
                'portfolio_title' => 'nullable|array',
                'portfolio_subtitle' => 'nullable|string',
                'portfolio_items' => 'nullable|array',
                'advantages_title' => 'nullable|array',
                'advantages_subtitle' => 'nullable|string',
                'advantages_left_items' => 'nullable|array',
                'advantages_right_items' => 'nullable|array',
                'hr_guide_title' => 'nullable|array',
                'hr_guide_subtitle' => 'nullable|string',
                'hr_guide_steps' => 'nullable|array',
                'demo_title' => 'nullable|array',
                'demo_points' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();
            $filtered = array_filter($data, function ($v) {
                return !is_null($v);
            });

            $content->update($filtered);

            return response()->json([
                'success' => true,
                'message' => 'Updated',
                'data' => $content
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // POST: create a new row (store)
    public function store(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'hero_title' => 'nullable|array',
                'hero_subheading' => 'nullable|string',
                'hero_button_text' => 'nullable|string',
                'hero_button_link' => 'nullable|string',
                'hero_image' => 'nullable|string',

                'empower_title' => 'nullable|array',
                'empower_description' => 'nullable|string',
                'empower_image' => 'nullable|string',

                'portfolio_title' => 'nullable|array',
                'portfolio_subtitle' => 'nullable|string',
                'portfolio_items' => 'nullable|array',

                'advantages_title' => 'nullable|array',
                'advantages_subtitle' => 'nullable|string',
                'advantages_left_items' => 'nullable|array',
                'advantages_right_items' => 'nullable|array',

                'hr_guide_title' => 'nullable|array',
                'hr_guide_subtitle' => 'nullable|string',
                'hr_guide_steps' => 'nullable|array',

                'demo_title' => 'nullable|array',
                'demo_points' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();
            $content = CorporateTrainingPageContent::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Created',
                'data' => $content
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // PUT/PATCH — Update latest or specific record by id
    public function update(Request $request, $id = null)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'hero_title' => 'nullable|array',
                'hero_subheading' => 'nullable|string',
                'hero_button_text' => 'nullable|string',
                'hero_button_link' => 'nullable|string',
                'hero_image' => 'nullable|string',

                'empower_title' => 'nullable|array',
                'empower_description' => 'nullable|string',
                'empower_image' => 'nullable|string',

                'portfolio_title' => 'nullable|array',
                'portfolio_subtitle' => 'nullable|string',
                'portfolio_items' => 'nullable|array',

                'advantages_title' => 'nullable|array',
                'advantages_subtitle' => 'nullable|string',
                'advantages_left_items' => 'nullable|array',
                'advantages_right_items' => 'nullable|array',

                'hr_guide_title' => 'nullable|array',
                'hr_guide_subtitle' => 'nullable|string',
                'hr_guide_steps' => 'nullable|array',

                'demo_title' => 'nullable|array',
                'demo_points' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();
            $record = $id ? CorporateTrainingPageContent::find($id) : CorporateTrainingPageContent::orderBy('id', 'desc')->first();

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
            $record = $id ? CorporateTrainingPageContent::find($id) : CorporateTrainingPageContent::orderBy('id', 'desc')->first();

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
