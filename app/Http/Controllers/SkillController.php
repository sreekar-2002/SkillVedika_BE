<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;

class SkillController extends Controller
{
    // GET /skills
    public function index()
    {
        return response()->json(Skill::orderBy('id', 'DESC')->get());
    }

    // POST /skills
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255'
        ]);

        $skill = Skill::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category
        ]);

        return response()->json([
            'message' => 'Skill created successfully',
            'skill' => $skill
        ], 201);
    }

    // PUT /skills/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255'
        ]);

        $skill = Skill::findOrFail($id);
        $skill->name = $request->name;
        $skill->description = $request->description;
        $skill->category = $request->category;
        $skill->save();

        return response()->json([
            'message' => 'Skill updated',
            'skill' => $skill
        ]);
    }

    // DELETE /skills/{id}
    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json([
            'message' => 'Skill deleted'
        ]);
    }
}
