<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiveDemoSetting;

class LiveDemoController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'nullable|array',          // JSON
                'subtitle' => 'nullable|array',       // JSON

                'nameLabel' => 'nullable|string',
                'emailLabel' => 'nullable|string',
                'mobileLabel' => 'nullable|string',
                'selectCoursesLabel' => 'nullable|string',
                'termsLabel' => 'nullable|string',

                'buttonLabel' => 'nullable|array',    // JSON {text, icon}
                'footerText' => 'nullable|string'
            ]);

            $record = LiveDemoSetting::updateOrCreate(['id' => 1], $data);

            return response()->json([
                'message' => 'Saved successfully',
                'data' => $record
            ], 200);
        } catch (\Throwable $e) {
            \Log::error("LiveDemo STORE ERROR: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $record = LiveDemoSetting::find(1);
            return response()->json([
                'data' => $record ?: new \stdClass()
            ], 200);
        } catch (\Throwable $e) {
            \Log::error("LiveDemo SHOW ERROR: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
