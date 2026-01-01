<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // POST: /api/courses/{id}/review
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $courseId
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment
            ]
        );

        return response()->json(['message' => 'Review submitted'], 201);
    }
}

