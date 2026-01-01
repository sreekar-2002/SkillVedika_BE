<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        try {
            // Optimize query: only load necessary relationships and columns
            // Use eager loading with select to reduce memory usage
            $courses = Course::with(['details' => function ($query) {
                $query->select('id', 'course_id', 'slug');
            }])->get();
            
            // Include slug in each course for frontend use
            $courses = $courses->map(function ($course) {
                $courseArray = $course->toArray();
                if ($course->details) {
                    $courseArray['slug'] = $course->details->slug;
                }
                return $courseArray;
            });
            
            return response()->json([
                'success' => true, 
                'data' => $courses
            ])->header('Cache-Control', 'public, max-age=300'); // 5 minutes cache
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('CourseController::index - Database error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Database error occurred while fetching courses'
            ], 500);
        } catch (\Exception $e) {
            \Log::error('CourseController::index - Error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred while fetching courses'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $course = Course::with('details')->find($id);
            if (!$course) return response()->json(['success' => false, 'message' => 'Course not found'], 404);

            $courseArray = $course->toArray();
            if ($course->details) {
                $courseArray['slug'] = $course->details->slug;
            }

            return response()->json([
                'success' => true,
                'course' => $courseArray,
                'details' => $course->details,
                'data' => $courseArray // Also include as 'data' for compatibility with admin frontend
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
            ]);

            $course = Course::create([
                'title'        => $request->title,
                'image'        => $request->image ?? null,
                'description'  => $request->description,
                'price'        => $request->price ?? 0,
                'rating'       => $request->rating ?? 0,
                'students'     => $request->students ?? 0,
                'category_id'  => $request->category_id,
                'status'       => $request->status ?? 'none',
                'mode'         => $request->mode ?? 'active',
            ]);

            return response()->json(['success' => true, 'message' => 'Created', 'data' => $course], 201);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $ve->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $course = Course::find($id);
            if (!$course) return response()->json(['success' => false, 'message' => 'Course not found'], 404);

            if ($request->filled('image') && is_string($request->image)) {
                $course->image = $request->image;
            }

            $course->update($request->all());

            return response()->json(['success' => true, 'message' => 'Course updated', 'data' => $course]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $course = Course::find($id);
            if (!$course) return response()->json(['success' => false, 'message' => 'Course not found'], 404);

            $course->delete();
            return response()->json(['success' => true, 'message' => 'Course deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
