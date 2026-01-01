<?php

namespace App\Http\Controllers;

use App\Models\CourseDetailsPageContentJobAssistanceProgram;
use Illuminate\Http\Request;

class CourseDetailsJobAssistanceController extends Controller
{
    // GET /api/course-details/job-assistance
    public function index()
    {
        $item = CourseDetailsPageContentJobAssistanceProgram::orderBy('id', 'desc')->first();
        return response()->json(['data' => $item ?? (object)[]]);
    }

    // GET /api/course-details/job-assistance/{id}
    public function show($id)
    {
        $item = CourseDetailsPageContentJobAssistanceProgram::find($id);
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json(['data' => $item]);
    }

    // POST /api/course-details/job-assistance
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|array',
            'subtitle' => 'nullable|string',
            'points' => 'nullable|array',
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

        $data = [
            'title' => $request->input('title', ['main' => 'Job Assistance Program']),
            'subtitle' => $request->input('subtitle'),
            'points' => $formattedPoints, // Store as [{id, title, desc}]
        ];

        $item = CourseDetailsPageContentJobAssistanceProgram::create($data);

        return response()->json(['success' => true, 'message' => 'Created', 'data' => $item], 201);
    }

    // PUT /api/course-details/job-assistance/{id}
    public function update(Request $request, $id)
    {
        $item = CourseDetailsPageContentJobAssistanceProgram::find($id);
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $request->validate([
            'title' => 'nullable|array',
            'subtitle' => 'nullable|string',
            'points' => 'nullable|array',
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

        $item->title = $request->input('title', ['main' => 'Job Assistance Program']);
        $item->subtitle = $request->input('subtitle');
        $item->points = $formattedPoints; // Store as [{id, title, desc}]
        $item->save();

        return response()->json(['success' => true, 'message' => 'Updated', 'data' => $item]);
    }
}
