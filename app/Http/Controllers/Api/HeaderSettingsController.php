<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeaderSetting;
use Illuminate\Http\Request;

class HeaderSettingsController extends Controller
{
    // GET latest header settings
    public function show()
    {
        $settings = HeaderSetting::orderBy('id', 'desc')->first();

        return response()->json($settings ?? (object)[]);
    }

    // UPDATE header settings (always updates last row)
    public function update(Request $request)
    {
        $settings = HeaderSetting::orderBy('id', 'desc')->first();

        if (!$settings) {
            return response()->json(['message' => 'Settings not found'], 404);
        }

        $validated = $request->validate([
            'logo' => 'nullable|string',
            'menu_items' => 'required|array',
        ]);

        $settings->update($validated);

        return response()->json([
            'message' => 'Header settings updated successfully',
            'data' => $settings
        ]);
    }

    // CREATE new row (if needed)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|string',
            'menu_items' => 'required|array',
        ]);

        $newRow = HeaderSetting::create($validated);

        return response()->json([
            'message' => 'Header settings created successfully',
            'data' => $newRow
        ], 201);
    }
}

