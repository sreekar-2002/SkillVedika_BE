<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FooterSetting;

class FooterSettingsController extends Controller
{
    // GET all footer settings
    public function index()
    {
        return response()->json(
            FooterSetting::orderBy('id', 'desc')->get()
        );
    }

    // GET latest footer settings
    public function show()
    {
        $footer = FooterSetting::orderBy('id', 'desc')->first();

        if (!$footer) {
            return response()->json(['message' => 'Footer settings not found'], 404);
        }

        return response()->json($footer);
    }

    // CREATE new footer settings
    public function store(Request $request)
    {
        $validated = $request->validate([
            'get_in_touch' => 'nullable|string',
            'email_placeholder' => 'nullable|string',
            'logo' => 'nullable|string',
            'about' => 'nullable|string',
            'explore' => 'nullable|string',
            'explore_links' => 'nullable|array',
            'support' => 'nullable|string',
            'support_links' => 'nullable|array',
            'contact' => 'nullable|string',
            'contact_details' => 'nullable|array',
            'follow_us' => 'nullable|string',
            'social_media_icons' => 'nullable|array',
            'social_links' => 'nullable|array',
            'copyright' => 'nullable|string',
        ]);

        $footer = FooterSetting::create($validated);

        return response()->json([
            'message' => 'Footer settings created successfully',
            'data' => $footer,
        ], 201);
    }

    // UPDATE latest footer settings (default) or a specific one
    public function update(Request $request, $id = null)
    {
        $footer = $id ? FooterSetting::find($id) : FooterSetting::orderBy('id', 'desc')->first();

        if (!$footer) {
            return response()->json(['message' => 'Footer settings not found'], 404);
        }

        $validated = $request->validate([
            'get_in_touch' => 'nullable|string',
            'email_placeholder' => 'nullable|string',
            'logo' => 'nullable|string',
            'about' => 'nullable|string',
            'explore' => 'nullable|string',
            'explore_links' => 'nullable|array',
            'support' => 'nullable|string',
            'support_links' => 'nullable|array',
            'contact' => 'nullable|string',
            'contact_details' => 'nullable|array',
            'follow_us' => 'nullable|string',
            'social_media_icons' => 'nullable|array',
            'social_links' => 'nullable|array',
            'copyright' => 'nullable|string',
        ]);

        $footer->update($validated);

        return response()->json([
            'message' => 'Footer settings updated successfully',
            'data' => $footer,
        ]);
    }

    // DELETE a specific footer setting
    public function destroy($id)
    {
        $footer = FooterSetting::find($id);

        if (!$footer) {
            return response()->json(['message' => 'Footer settings not found'], 404);
        }

        $footer->delete();

        return response()->json([
            'message' => 'Footer settings deleted successfully',
        ]);
    }
}

