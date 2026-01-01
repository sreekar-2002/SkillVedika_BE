<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EnrollmentController extends Controller
{

    
    // Constants
    private const EMPTY_PLACEHOLDER = '(empty)';
    private const MAX_LIMIT = 100;
    private const DEFAULT_LIMIT = 20;
    private const DEFAULT_PAGE = 1;

    /**
     * Format item IDs preview for logging
     */
    private function formatItemIdsPreview($itemIds)
    {
        if (count($itemIds) === 0) {
            return 'none';
        }

        $preview = implode(', ', array_slice($itemIds, 0, 5));
        if (count($itemIds) > 5) {
            $preview .= '...';
        }

        return $preview;
    }

    /**
     * Apply search filter to query
     */
    private function applySearchFilter($query, $search)
    {
        if ($search === '') {
            return;
        }

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('message', 'like', "%{$search}%");

            // Search course names: Get course IDs that match the search term
            $matchingCourseIds = \App\Models\Course::where('title', 'like', "%{$search}%")
                ->pluck('id')
                ->toArray();

            if (!empty($matchingCourseIds)) {
                // Search for enrollments where courses JSON array contains any matching course ID
                foreach ($matchingCourseIds as $courseId) {
                    $q->orWhereJsonContains('courses', (int) $courseId)
                      ->orWhereJsonContains('courses', (string) $courseId);
                }
            }
        });
    }

    /**
     * Apply status filter to query
     */
    private function applyStatusFilter($query, $status)
    {
        if ($status !== '') {
            $statusValue = trim($status);
            // Case-insensitive match
            $query->whereRaw('LOWER(TRIM(status)) = LOWER(?)', [$statusValue]);
        }
    }

    /**
     * Apply course filter to query
     */
    private function applyCourseFilter($query, $course)
    {
        if ($course === '') {
            return;
        }

        $courseValue = trim($course);
        if (is_numeric($courseValue)) {
            // Course ID filter - try both int and string representations
            $query->where(function ($q) use ($courseValue) {
                $q->whereJsonContains('courses', (int) $courseValue)
                  ->orWhereJsonContains('courses', (string) $courseValue);
            });
        } else {
            // Course name filter (if needed)
            $query->whereJsonContains('courses', $courseValue);
        }
    }

    /**
     * Apply date filters to query
     */
    private function applyDateFilters($query, $dateFrom, $dateTo)
    {
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
    }

    /**
     * Validate and normalize sort parameters
     */
    private function normalizeSortParams($request)
    {
        $sortBy = $request->get('sort_by', 'id');
        $sortDir = strtolower($request->get('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        // Frontend sends "course", DB uses JSON -> disable or map safely
        if ($sortBy === 'course') {
            $sortBy = 'id'; // safest fallback
        }

        $allowedSorts = [
            'id',
            'name',
            'email',
            'status',
            'created_at',
            'contacted_on'
        ];

        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'id';
        }

        return ['sortBy' => $sortBy, 'sortDir' => $sortDir];
    }

    /**
     * GET /api/leads
     * Admin listing endpoint with filters, sorting & pagination
     */
    public function index(Request $request)
    {
        try {
            Log::info('[EnrollmentController] GET /api/leads', $request->all());

            /* ------------------------
               BASIC PARAMS
            ------------------------ */
            // CRITICAL: Ensure page and limit are valid
            $limit = max(1, min(self::MAX_LIMIT, (int) $request->get('limit', self::DEFAULT_LIMIT)));
            $page = max(1, (int) $request->get('page', self::DEFAULT_PAGE));
            $search = trim((string) $request->get('search', ''));
            $status = trim((string) $request->get('status', ''));
            $course = trim((string) $request->get('course', ''));
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            // Normalize sort parameters
            $sortParams = $this->normalizeSortParams($request);

            // Debug logging - log all request parameters
            Log::info('[EnrollmentController] 📥 Received request params', [
                'all_params' => $request->all(),
                'search' => $search !== '' ? $search : self::EMPTY_PLACEHOLDER,
                'status' => $status !== '' ? $status : self::EMPTY_PLACEHOLDER,
                'course' => $course !== '' ? $course : self::EMPTY_PLACEHOLDER,
                'date_from' => !empty($dateFrom) ? $dateFrom : self::EMPTY_PLACEHOLDER,
                'date_to' => !empty($dateTo) ? $dateTo : self::EMPTY_PLACEHOLDER,
                'sort_by' => $sortParams['sortBy'],
                'sort_dir' => $sortParams['sortDir'],
                'page' => $page,
                'limit' => $limit,
            ]);

            /* ------------------------
               BASE QUERY
            ------------------------ */
            $query = Enrollment::query();

            // Apply filters
            $this->applySearchFilter($query, $search);
            $this->applyStatusFilter($query, $status);
            $this->applyCourseFilter($query, $course);
            $this->applyDateFilters($query, $dateFrom, $dateTo);

            /* ------------------------
               SORT
            ------------------------ */
            $query->orderBy($sortParams['sortBy'], $sortParams['sortDir']);

            /* ------------------------
               PAGINATION
               Laravel's paginate() automatically calculates:
               - offset = (page - 1) * limit
               - total pages = ceil(total / limit)
            ------------------------ */
            // CRITICAL: Laravel's paginate() reads 'page' from request query string by default
            // We need to ensure the page parameter is correctly set in the query string
            // The 4th parameter of paginate() should work, but to be absolutely sure:

            // Store original page value from query
            $originalPage = $request->query('page');

            // CRITICAL: Set the page in the query string (not request body)
            // This ensures paginate() reads the correct page
            $request->query->set('page', (string)$page);

            // Now paginate - it will use the page from query string (which we just set)
            // Also pass as 4th parameter as fallback
            $paginator = $query->paginate($limit, ['*'], 'page', $page);

            // Restore original page in query (for safety)
            if ($originalPage !== null) {
                $request->query->set('page', (string)$originalPage);
            } else {
                $request->query->remove('page');
            }

            Log::info('[EnrollmentController] 📄 Pagination setup', [
                'calculated_page' => $page,
                'limit' => $limit,
                'request_page_before' => $originalPage,
                'request_page_after_set' => $request->query('page'),
                'paginator_current_page' => $paginator->currentPage(),
                'paginator_total' => $paginator->total(),
                'paginator_last_page' => $paginator->lastPage(),
            ]);

            // Laravel's paginate() automatically handles out-of-bounds pages:
            // - If page > lastPage, it returns lastPage
            // - If page < 1, it returns page 1
            // We should trust Laravel's currentPage() which reflects what was actually returned
            $actualPage = $paginator->currentPage();

            // CRITICAL: Verify pagination is working correctly
            $firstItemOnPage = $paginator->firstItem(); // First item number on this page
            $lastItemOnPage = $paginator->lastItem();   // Last item number on this page
            $itemsOnPage = count($paginator->items());

            // CRITICAL: Log the actual item IDs being returned to verify pagination is working
            $itemIds = array_map(function($item) {
                return $item->id ?? 'no-id';
            }, $paginator->items());
            $itemIdsPreview = $this->formatItemIdsPreview($itemIds);

            Log::info('[EnrollmentController] 🔍 Query execution', [
                'requested_page' => $page,
                'actual_page' => $actualPage,
                'limit' => $limit,
                'total_results' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'first_item' => $firstItemOnPage,
                'last_item' => $lastItemOnPage,
                'items_on_page' => $itemsOnPage,
                'expected_range' => "Items " . (($actualPage - 1) * $limit + 1) . " to " . ($actualPage * $limit),
                'item_ids_preview' => $itemIdsPreview,
                'sql'      => $query->toSql(),
                'bindings' => $query->getBindings(),
                'applied_filters' => [
                    'search' => $search !== '' ? $search : 'none',
                    'status' => $status !== '' ? $status : 'none',
                    'course' => $course !== '' ? $course : 'none',
                    'date_from' => !empty($dateFrom) ? $dateFrom : 'none',
                    'date_to' => !empty($dateTo) ? $dateTo : 'none',
                ],
            ]);

            // CRITICAL: Return consistent pagination metadata
            // Always use actualPage to ensure frontend sync
            $response = [
                'data'         => $paginator->items(),
                'current_page' => $actualPage,
                'last_page'    => max(1, $paginator->lastPage()), // Ensure at least 1
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
            ];

            Log::info('[EnrollmentController] ✅ Response', [
                'items_count' => count($response['data']),
                'total' => $response['total'],
                'current_page' => $response['current_page'],
                'last_page' => $response['last_page'],
            ]);

            return response()->json($response);

        } catch (\Throwable $e) {
            Log::error('[EnrollmentController] ERROR', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Server Error',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/leads/{id}
     */
    public function show($id)
    {
        return Enrollment::findOrFail($id);
    }

    /**
     * POST /api/enroll (public)
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'phone',
            'courses'
        ]);

        // Everything else -> meta
        $data['meta'] = $request->except([
            'name',
            'email',
            'phone',
            'courses'
        ]);

        $enrollment = Enrollment::create($data);

        return response()->json([
            'success' => true,
            'data'    => $enrollment,
        ], 201);
    }

    /**
     * PUT /api/leads/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'       => 'sometimes|string|in:New,Contacted,Closed',
            'admin_notes'  => 'nullable|string',
            'message'      => 'nullable|string',
            'contacted_on' => 'nullable|date',
        ]);

        $lead = Enrollment::findOrFail($id);

        if ($request->filled('status')) {
            $lead->status = $request->status;

            if ($request->status === 'Contacted' && !$lead->contacted_on) {
                $lead->contacted_on = now();
            }
        }

        if ($request->filled('admin_notes')) {
            $lead->admin_notes = $request->admin_notes;
        }

        if ($request->filled('message')) {
            $lead->message = $request->message;
        }

        if ($request->filled('contacted_on')) {
            $lead->contacted_on = Carbon::parse($request->contacted_on);
        }

        $lead->save();

        return response()->json([
            'success' => true,
            'data'    => $lead,
        ]);
    }

    /**
     * DELETE /api/leads/{id}
     */
    public function destroy($id)
    {
        Enrollment::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    /**
     * POST /api/leads/delete-multiple
     */
    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);

        Enrollment::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success'     => true,
            'deleted_ids'=> $request->ids,
        ]);
    }

    /**
     * GET /api/leads/export
     * Export all filtered leads (no pagination)
     */
    public function export(Request $request)
    {
        try {
            $search = trim($request->get('search', ''));
            $status = trim($request->get('status', ''));
            $course = trim($request->get('course', ''));
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            // Normalize sort parameters
            $sortParams = $this->normalizeSortParams($request);

            $query = Enrollment::query();

            // Apply same filters as index method
            $this->applySearchFilter($query, $search);
            $this->applyStatusFilter($query, $status);
            $this->applyCourseFilter($query, $course);
            $this->applyDateFilters($query, $dateFrom, $dateTo);

            $query->orderBy($sortParams['sortBy'], $sortParams['sortDir']);

            // Get all results (no pagination)
            $leads = $query->get();

            return response()->json([
                'success' => true,
                'data'    => $leads,
                'count'   => $leads->count(),
            ]);
        } catch (\Throwable $e) {
            Log::error('[EnrollmentController] Export ERROR', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Export failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
