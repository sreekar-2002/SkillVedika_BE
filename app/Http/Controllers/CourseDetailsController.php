<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CourseDetail;
use App\Models\Course;

class CourseDetailsController extends Controller
{
    // Constants for error messages
    private const ERROR_COURSE_DETAILS_NOT_FOUND = 'Course details not found';

    /**
     * GET /api/course-details - List all or filter by course_id query param
     */
    public function index(Request $request)
    {
        try {
            $courseId = $request->query('course_id');
            if ($courseId) {
                $details = CourseDetail::where('course_id', $courseId)->get();
                return response()->json(['success' => true, 'data' => $details]);
            }

            return response()->json(['success' => true, 'data' => CourseDetail::all()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Find course detail by identifier (slug, ID, or course_id)
     */
    private function findCourseDetail($identifier)
    {
        // Try finding by slug first (non-numeric strings)
        if (!is_numeric($identifier)) {
            $detail = CourseDetail::where('slug', $identifier)->first();
            if ($detail) {
                return $detail;
            }
        }

        // Check if it's a course_id (numeric and exists in courses table) or a detail ID
        $detail = CourseDetail::find($identifier);

        // If not found by ID, try finding by course_id
        if (!$detail) {
            $detail = CourseDetail::where('course_id', $identifier)->first();
        }

        return $detail;
    }

    /**
     * GET /api/course-details/{identifier} - Get course details by ID, slug, or course_id
     * Supports:
     * - /api/course-details/{id} - Get by detail ID (for admin frontend)
     * - /api/course-details/{slug} - Get by slug (for public website)
     * - /api/course-details/{courseId} - Get by course_id (for reference compatibility)
     */
    public function show($identifier = null)
    {
        try {
            $detail = $this->findCourseDetail($identifier);

            if (!$detail) {
                return response()->json(['message' => self::ERROR_COURSE_DETAILS_NOT_FOUND], 404);
            }

            // Match reference format: return { data: record }
            return response()->json(['data' => $detail], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Parse meta_json from request
     */
    private function parseMetaJson($metaJson)
    {
        if (is_array($metaJson)) {
            return $metaJson;
        }

        if ($metaJson) {
            $decoded = json_decode($metaJson, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    /**
     * Generate a unique slug from course title
     */
    private function generateSlug($title, $courseId, $excludeId = null)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (CourseDetail::where('slug', $slug)
            ->where('course_id', '!=', $courseId)
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function store(Request $request)
    {
        try {
            // Custom validation for slug to handle empty strings
            $slug = $request->slug;
            if (empty($slug) || trim($slug) === '') {
                $slug = null;
            }

            $request->merge(['slug' => $slug]);

            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'slug' => 'nullable|string|max:255|unique:course_details,slug'
            ], [
                'slug.unique' => 'This slug is already in use. Please choose a different slug or leave it empty to auto-generate.'
            ]);

            $incomingMeta = $this->parseMetaJson($request->meta_json);

            $incomingMeta['sections'] = array_filter([
                'why_choose' => [
                    'title' => $request->why_choose_title ?? null,
                    'description' => $request->why_choose_description ?? null,
                ],
                'who_should_join' => [
                    'title' => $request->who_should_join_title ?? null,
                    'description' => $request->who_should_join_description ?? null,
                ],
                'key_outcomes' => [
                    'title' => $request->key_outcomes_title ?? null,
                    'description' => $request->key_outcomes_description ?? null,
                ],
            ]);

            // Generate slug if not provided (after validation passes)
            $finalSlug = $request->slug;
            if (empty($finalSlug)) {
                $course = Course::find($request->course_id);
                if ($course) {
                    $finalSlug = $this->generateSlug($course->title, $request->course_id);
                }
            }

            $details = CourseDetail::create([
                'course_id'        => $request->course_id,
                'slug'             => $finalSlug,
                'subtitle'         => $request->subtitle,
                'skill'            => $request->skill,
                'trainers'         => $request->trainers,
                'agenda'           => $request->agenda,
                'why_choose'       => $request->why_choose,
                'who_should_join'  => $request->who_should_join,
                'key_outcomes'     => $request->key_outcomes,
                'meta_title'       => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords'    => $request->meta_keywords,
                'meta_json'        => $incomingMeta,
            ]);

            return response()->json(['success' => true, 'message' => 'Step 2 saved', 'data' => $details], 201);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $ve->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Normalize slug from request
     */
    private function normalizeSlug($slug)
    {
        if (empty($slug) || trim($slug) === '') {
            return null;
        }
        return trim($slug);
    }

    /**
     * Handle slug generation logic
     */
    private function handleSlugGeneration($incoming, $details)
    {
        // Handle slug generation if not provided but course title changed
        if (empty($incoming['slug']) && isset($incoming['course_id'])) {
            $course = Course::find($incoming['course_id']);
            if ($course) {
                $incoming['slug'] = $this->generateSlug($course->title, $incoming['course_id'], $details->id);
            }
        } elseif (!empty($incoming['slug'])) {
            // Slug uniqueness is already validated above, but ensure it's properly formatted
            $incoming['slug'] = trim($incoming['slug']);
        } else {
            // Generate slug if empty
            $course = Course::find($details->course_id);
            if ($course) {
                $incoming['slug'] = $this->generateSlug($course->title, $details->course_id, $details->id);
            }
        }

        return $incoming;
    }

    /**
     * Update section data from request
     */
    private function updateSection($sections, $request, $sectionKey, $incoming)
    {
        $titleKey = "{$sectionKey}_title";
        $descriptionKey = "{$sectionKey}_description";

        if ($request->has($titleKey) || $request->has($descriptionKey)) {
            $sections[$sectionKey] = array_merge($sections[$sectionKey] ?? [], [
                'title' => $request->input($titleKey, $sections[$sectionKey]['title'] ?? null),
                'description' => $request->input($descriptionKey, $sections[$sectionKey]['description'] ?? null),
            ]);
            unset($incoming[$titleKey], $incoming[$descriptionKey]);
        }

        return ['sections' => $sections, 'incoming' => $incoming];
    }

    /**
     * Validate and process update request
     */
    private function processUpdateRequest($request, $details)
    {
        // Normalize slug
        $slug = $this->normalizeSlug($request->slug);
        $request->merge(['slug' => $slug]);

        $request->validate([
            'slug' => 'nullable|string|max:255|unique:course_details,slug,' . $details->id
        ], [
            'slug.unique' => 'This slug is already in use by another course. Please choose a different slug or leave it empty to auto-generate.'
        ]);

        $incoming = $request->all();
        $incoming = $this->handleSlugGeneration($incoming, $details);

        $meta = $this->parseMetaJson($details->meta_json);
        $sections = $meta['sections'] ?? [];

        // Update sections
        $result = $this->updateSection($sections, $request, 'why_choose', $incoming);
        $sections = $result['sections'];
        $incoming = $result['incoming'];

        $result = $this->updateSection($sections, $request, 'who_should_join', $incoming);
        $sections = $result['sections'];
        $incoming = $result['incoming'];

        $result = $this->updateSection($sections, $request, 'key_outcomes', $incoming);
        $sections = $result['sections'];
        $incoming = $result['incoming'];

        $meta['sections'] = $sections;
        $incoming['meta_json'] = $meta;

        return $incoming;
    }

    /**
     * PUT /api/course-details/{id} - Update by detail ID (for admin frontend)
     * Also supports /api/course-details/{courseId} - Update by course_id (for reference compatibility)
     */
    public function update(Request $request, $id)
    {
        // Try finding by ID first, then by course_id
        $details = $this->findCourseDetail($id);

        if (!$details) {
            return response()->json(['success' => false, 'message' => self::ERROR_COURSE_DETAILS_NOT_FOUND], 404);
        }

        try {
            $incoming = $this->processUpdateRequest($request, $details);
            $details->update($incoming);
            return response()->json(['success' => true, 'message' => 'Details updated', 'data' => $details]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $ve->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /api/course-details/{id} - Delete course details by ID or course_id
     */
    public function destroy($id)
    {
        try {
            // Try finding by ID first, then by course_id
            $details = $this->findCourseDetail($id);

            if (!$details) {
                return response()->json(['success' => false, 'message' => self::ERROR_COURSE_DETAILS_NOT_FOUND], 404);
            }

            $details->delete();

            return response()->json([
                'success' => true,
                'message' => 'Course details deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
