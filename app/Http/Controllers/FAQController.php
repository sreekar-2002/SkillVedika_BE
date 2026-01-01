<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaqController extends Controller
{
    private const ERROR_FAQ_NOT_FOUND = 'FAQ not found';

    public function index()
    {
        try {
            $faqs = FAQ::orderBy("id", "DESC")->get();
            return response()->json([
                "faqs" => $faqs
            ]);
        } catch (\Exception $e) {
            Log::error('FAQController::index error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Always return error details for debugging (can be restricted in production later)
            return response()->json([
                "message" => "Server Error",
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }

    public function show($id)
    {
        $faq = FAQ::find($id);

        if (!$faq) {
            return response()->json(["message" => self::ERROR_FAQ_NOT_FOUND], 404);
        }

        return response()->json($faq);
    }

    public function store(Request $request)
    {
        $request->validate([
            "question" => "required|string",
            "answer" => "nullable|string",
            "show" => "required|boolean"
        ]);

        $faq = FAQ::create($request->all());

        return response()->json([
            "message" => "FAQ created successfully",
            "faq" => $faq
        ]);
    }

    public function update(Request $request, $id)
    {
        $faq = FAQ::find($id);

        if (!$faq) {
            return response()->json(["message" => self::ERROR_FAQ_NOT_FOUND], 404);
        }

        $request->validate([
            "question" => "required|string",
            "answer" => "nullable|string",
            "show" => "required|boolean"
        ]);

        $faq->update($request->all());

        return response()->json([
            "message" => "FAQ updated successfully",
            "faq" => $faq
        ]);
    }

    public function destroy($id)
    {
        $faq = FAQ::find($id);

        if (!$faq) {
            return response()->json(["message" => self::ERROR_FAQ_NOT_FOUND], 404);
        }

        $faq->delete();

        return response()->json([
            "message" => "FAQ deleted successfully"
        ]);
    }
}
