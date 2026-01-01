<?php

namespace App\Http\Controllers;

use App\Models\HrFaq;
use App\Http\Services\DynamicService;
use Illuminate\Http\Request;

class HrFaqController extends Controller
{
    public $modelClass = HrFaq::class;

    public function index()
    {
        // Only return HR FAQs that are marked as 1 (true) in the show column.
        return response()->json(
            HrFaq::where('show', 1)->orderBy('id', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $hrFaq = DynamicService::store(new HrFaq, $request->all());
        return response()->json([
            'success' => true,
            'message' => 'HR FAQ created successfully',
            'data' => $hrFaq
        ], 201);
    }

    public function update(Request $request, HrFaq $hr_faq)
    {
        $hrFaq = DynamicService::update($hr_faq, $request->all());
        return response()->json([
            'success' => true,
            'message' => 'HR FAQ updated successfully',
            'data' => $hrFaq
        ]);
    }

    public function destroy(HrFaq $hr_faq)
    {
        $hr_faq->delete();
        return response()->json([
            'success' => true,
            'message' => 'HR FAQ deleted successfully'
        ]);
    }

    // Show single HR FAQ
    public function show($id)
    {
        $hrFaq = HrFaq::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $hrFaq
        ]);
    }
}

