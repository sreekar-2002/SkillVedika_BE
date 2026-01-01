<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\HeaderSetting;

class HeaderSettingController extends Controller
{
    public function index()
    {
        // Return the latest record or create an empty one
        $settings = HeaderSetting::orderBy('id', 'desc')->first();

        if (!$settings) {
            $settings = HeaderSetting::create([
                'logo' => '',
                'menu_items' => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function update(Request $request)
    {
        Log::info('HeaderSetting update request:', $request->all());

        $validated = $request->validate([
            'logo' => 'nullable|string',
            'menu_items' => 'nullable|array',
            'menu_items.*.slug' => 'nullable|string',
            'menu_items.*.text' => 'nullable|string',
            'menu_items.*.new_tab' => 'nullable|boolean',
        ]);

        Log::info('HeaderSetting validated data:', $validated);

        $settings = HeaderSetting::orderBy('id', 'desc')->first();

        if (!$settings) {
            $settings = new HeaderSetting();
        }

        // Update logo
        if (isset($validated['logo'])) {
            Log::info('Setting logo to: ' . $validated['logo']);
            $settings->logo = $validated['logo'];
        }

        // Update menu_items (allow empty array)
        if (isset($validated['menu_items'])) {
            Log::info('Setting menu_items to:', $validated['menu_items']);
            $settings->menu_items = $validated['menu_items'];
        }

        $settings->save();

        // Refresh and return the saved data
        $settings->refresh();

        Log::info('HeaderSetting saved:', $settings->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Header settings updated successfully!',
            'data' => $settings
        ]);
    }

    // GET /api/header-settings/{id}
    public function show($id)
    {
        $settings = HeaderSetting::find($id);

        if (!$settings) {
            return response()->json(['message' => 'Header settings not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    // POST /api/header-settings (create new)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|string',
            'menu_items' => 'required|array',
        ]);

        $settings = HeaderSetting::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Header settings created successfully',
            'data' => $settings
        ], 201);
    }

    // DELETE /api/header-settings/{id}
    public function destroy($id)
    {
        $settings = HeaderSetting::find($id);

        if (!$settings) {
            return response()->json(['message' => 'Header settings not found'], 404);
        }

        $settings->delete();

        return response()->json([
            'success' => true,
            'message' => 'Header settings deleted successfully'
        ]);
    }
}
