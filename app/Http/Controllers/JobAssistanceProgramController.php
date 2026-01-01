<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseDetailsPageContentJobAssistanceProgram;

class JobAssistanceProgramController extends Controller
{
    public function index()
    {
        try {
            // Return records with newest first so callers that take the first element
            // (e.g. frontend using data[0]) receive the most recent row.
            $records = CourseDetailsPageContentJobAssistanceProgram::orderBy('id', 'desc')->get();

            if ($records->isEmpty()) {
                // When no data exists, return JSON null so clients that
                // check for falsy responses can treat it as "no content".
                // The website frontend expects either an array or null.
                return response()->json(null);
            }

            // Format each record to handle JSON fields properly
            $formatted = $records->map(function ($record) {
                // Handle title - could be string or JSON
                $titleValue = $record->title;
                if (is_string($titleValue)) {
                    $decoded = json_decode($titleValue, true);
                    $title = is_array($decoded) ? $decoded : ['main' => $titleValue];
                } else {
                    $title = is_array($titleValue) ? $titleValue : ['main' => $titleValue ?? 'Job Assistance Program'];
                }

                // Handle points - could be string (JSON) or array
                $pointsValue = $record->points;
                if (is_string($pointsValue)) {
                    $points = json_decode($pointsValue, true) ?? [];
                } else {
                    $points = is_array($pointsValue) ? $pointsValue : [];
                }

                return [
                    'id' => $record->id,
                    'title' => $title,
                    'subtitle' => $record->subtitle ?? '',
                    'points' => $points,
                ];
            });

            return response()->json($formatted);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Make $id optional: some callers (or mis-routed requests) may invoke this
    // without an {id} parameter — handle that gracefully by returning the
    // latest record (same behaviour as before).
    public function show($id = null)
    {
        try {
            $record = CourseDetailsPageContentJobAssistanceProgram::orderBy('id', 'desc')->first();

            if (!$record) {
                return response()->json(['message' => 'Not Found'], 404);
            }

            // Format the record
            $titleValue = $record->title;
            if (is_string($titleValue)) {
                $decoded = json_decode($titleValue, true);
                $title = is_array($decoded) ? $decoded : ['main' => $titleValue];
            } else {
                $title = is_array($titleValue) ? $titleValue : ['main' => $titleValue ?? 'Job Assistance Program'];
            }

            $pointsValue = $record->points;
            if (is_string($pointsValue)) {
                $points = json_decode($pointsValue, true) ?? [];
            } else {
                $points = is_array($pointsValue) ? $pointsValue : [];
            }

            return response()->json([
                'id' => $record->id,
                'title' => $title,
                'subtitle' => $record->subtitle ?? '',
                'points' => $points,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'nullable|array',
            'subtitle' => 'nullable|string',
            'points'   => 'nullable|array'
        ]);

        // Ensure points are properly formatted: [{id, title, desc}]
        $points = $request->input('points', []);
        $formattedPoints = [];
        foreach ($points as $point) {
            $formattedPoints[] = [
                'id' => $point['id'] ?? null,
                'title' => $point['title'] ?? '',
                'desc' => $point['desc'] ?? $point['description'] ?? '',
            ];
        }

        $validated['points'] = $formattedPoints;

        $created = CourseDetailsPageContentJobAssistanceProgram::create($validated);

        return response()->json($created, 201);
    }

    public function update(Request $request, $id)
    {
        $program = CourseDetailsPageContentJobAssistanceProgram::orderBy('id', 'desc')->first();

        if (!$program) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $validated = $request->validate([
            'title'    => 'nullable|array',
            'subtitle' => 'nullable|string',
            'points'   => 'nullable|array'
        ]);

        // Ensure points are properly formatted: [{id, title, desc}]
        $points = $request->input('points', []);
        $formattedPoints = [];
        foreach ($points as $point) {
            $formattedPoints[] = [
                'id' => $point['id'] ?? null,
                'title' => $point['title'] ?? '',
                'desc' => $point['desc'] ?? $point['description'] ?? '',
            ];
        }

        $validated['points'] = $formattedPoints;

        $program->update($validated);

        return response()->json($program);
    }

    public function destroy($id)
    {
        $program = CourseDetailsPageContentJobAssistanceProgram::orderBy('id', 'desc')->first();

        if (!$program) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $program->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
