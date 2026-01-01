<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Blog;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $query = $request->query('q', '');

        if (trim($query) === '') {
            return response()->json([
                'courses' => [],
                'blogs' => [],
                'popular' => [],
                'categories' => [],
            ]);
        }

        try {
            // Search Example — adjust if you want
            $courses = Course::where('title', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'title', 'image', 'instructor']);

            $blogs = Blog::where('title', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'title']);

            return response()->json([
                'courses' => $courses,
                'blogs' => $blogs,
                'popular' => [],
                'categories' => [],
            ]);
        } catch (\Throwable $e) {
            // Log the error and return an empty, non-500 response so frontend doesn't break
            Log::error('Search suggestions failed: ' . $e->getMessage());

            return response()->json([
                'courses' => [],
                'blogs' => [],
                'popular' => [],
                'categories' => [],
                'error' => 'search_unavailable'
            ]);
        }
    }
}

