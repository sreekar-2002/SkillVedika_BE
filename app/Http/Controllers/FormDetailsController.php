<?php

namespace App\Http\Controllers;

use App\Models\FormDetail;
use Illuminate\Http\Request;

class FormDetailsController extends Controller
{
    private const ERROR_FORM_DETAILS_NOT_FOUND = 'Form details not found.';

    /**
     * Display all form details.
     */
    public function index()
    {
        $form = FormDetail::orderBy('id', 'desc')->first();
        return response()->json([
            'success' => true,
            'data' => $form ?? (object)[]
        ]);
    }

    /**
     * Store newly created form details.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'form_title'            => 'nullable|string',
            'form_subtitle'         => 'nullable|string',
            'full_name_label'       => 'nullable|string',
            'full_name_placeholder' => 'nullable|string',
            'email_label'           => 'nullable|string',
            'email_placeholder'     => 'nullable|string',
            'course_label'          => 'nullable|string',
            'course_placeholder'    => 'nullable|string',
            'terms_prefix'          => 'nullable|string',
            'terms_label'           => 'nullable|string',
            'terms_link'            => 'nullable|string',
            'submit_button_text'    => 'nullable|string',
            'form_footer_text'      => 'nullable|string',
        ]);

        $form = FormDetail::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Form details created successfully.',
            'data' => $form
        ], 201);
    }

    /**
     * Display a specific form details.
     */
    public function show($id)
    {
        $form = FormDetail::find($id);

        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => self::ERROR_FORM_DETAILS_NOT_FOUND
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $form
        ]);
    }

    /**
     * Update form details.
     */
    public function update(Request $request, $id = null)
    {
        // If no id provided, update the latest record
        $form = $id ? FormDetail::find($id) : FormDetail::orderBy('id', 'desc')->first();

        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => self::ERROR_FORM_DETAILS_NOT_FOUND
            ], 404);
        }

        $validated = $request->validate([
            'form_title'            => 'nullable|string',
            'form_subtitle'         => 'nullable|string',
            'full_name_label'       => 'nullable|string',
            'full_name_placeholder' => 'nullable|string',
            'email_label'           => 'nullable|string',
            'email_placeholder'     => 'nullable|string',
            'course_label'          => 'nullable|string',
            'course_placeholder'    => 'nullable|string',
            'terms_prefix'          => 'nullable|string',
            'terms_label'           => 'nullable|string',
            'terms_link'            => 'nullable|string',
            'submit_button_text'    => 'nullable|string',
            'form_footer_text'      => 'nullable|string',
        ]);

        $form->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Form details updated successfully.',
            'data' => $form
        ]);
    }

    /**
     * Delete form details.
     */
    public function destroy($id = null)
    {
        $form = $id ? FormDetail::find($id) : FormDetail::orderBy('id', 'desc')->first();

        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => self::ERROR_FORM_DETAILS_NOT_FOUND
            ], 404);
        }

        $form->delete();

        return response()->json([
            'success' => true,
            'message' => 'Form details deleted successfully.'
        ]);
    }
}
