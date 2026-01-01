<?php

namespace App\Http\Controllers;

use App\Models\HomePageContent;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    // Fetch homepage data
    public function index()
    {
        $home = HomePageContent::orderBy('id', 'desc')->first();

        return response()->json($home);
    }

    // POST — create a new homepage content row
    public function store(Request $request)
    {
        $data = $request->validate([
            "hero_heading" => "nullable|string",
            "hero_content" => "nullable|array",
            "hero_popular" => "nullable|array",
            "hero_image" => "nullable|string",

            "explore_heading" => "nullable|string",
            "explore_content" => "nullable|string",
            "explore_tabs" => "nullable|array",

            "key_features_title" => "nullable|string",
            "key_features_content" => "nullable|string",
            "key_features_points" => "nullable|array",

            "job_assistance_heading" => "nullable|string",
            "job_assistance_content" => "nullable|string",
            "job_assistance_points" => "nullable|array",

            "job_support_title" => "nullable|string",
            "job_support_content" => "nullable|string",
            "job_support_payment_types" => "nullable|array",
            "job_support_button" => "nullable|string",
            "job_support_button_link" => "nullable|string",

            "blog_section_heading" => "nullable|string",
        ]);

        $record = HomePageContent::create($data);

        return response()->json(['message' => 'Created', 'data' => $record], 201);
    }

    // PUT/PATCH — update latest or specific record
    public function update(Request $request, $id = null)
    {
        $data = $request->validate([
            "hero_heading" => "nullable|string",
            "hero_content" => "nullable|array",
            "hero_popular" => "nullable|array",
            "hero_image" => "nullable|string",

            "explore_heading" => "nullable|string",
            "explore_content" => "nullable|string",
            "explore_tabs" => "nullable|array",

            "key_features_title" => "nullable|string",
            "key_features_content" => "nullable|string",
            "key_features_points" => "nullable|array",

            "job_assistance_heading" => "nullable|string",
            "job_assistance_content" => "nullable|string",
            "job_assistance_points" => "nullable|array",

            "job_support_title" => "nullable|string",
            "job_support_content" => "nullable|string",
            "job_support_payment_types" => "nullable|array",
            "job_support_button" => "nullable|string",
            "job_support_button_link" => "nullable|string",

            "blog_section_heading" => "nullable|string",
        ]);

        $record = $id ? HomePageContent::find($id) : HomePageContent::orderBy('id', 'desc')->first();

        if (!$record) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $filtered = array_filter($data, function ($v) { return !is_null($v); });

        $record->update($filtered);

        return response()->json(['message' => 'Updated', 'data' => $record], 200);
    }

    // DELETE — remove specific or latest record
    public function destroy($id = null)
    {
        $record = $id ? HomePageContent::find($id) : HomePageContent::orderBy('id', 'desc')->first();

        if (!$record) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $record->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }
}
