<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseDetailsPageContentPlacementsAndReserve;

class PlacementsReserveController extends Controller
{
    public function index()
    {
        try {
            $record = CourseDetailsPageContentPlacementsAndReserve::orderBy('id', 'desc')->first();

            if (!$record) {
                // Return default/empty structure instead of null for better frontend handling
                return response()->json([
                    'id' => null,
                    'title' => 'Placements & Reserve',
                    'description' => '',
                    'placements_title' => ['main' => 'Placement Partners'],
                    'placements_subtitle' => '',
                    'placement_images' => [],
                    'reserve_title' => ['main' => 'Reserve Your Spot'],
                    'reserve_subtitle' => 'Join our exclusive program',
                    'reserve_block1' => ['0', 'Days'],
                    'reserve_block2' => ['0', 'Hours'],
                    'reserve_block3' => ['0', 'Minutes'],
                    'reserve_button_name' => 'Enroll Now',
                    'reserve_button_link' => '',
                ])->header('Cache-Control', 'public, max-age=300');
            }

            // Format the response - handle JSON fields that might be stored as strings
            $formatted = [
                'id' => $record->id,
                'title' => $this->parseField($record->placements_title, 'Placements & Reserve'),
                'description' => $record->placements_subtitle ?? '',
                    'placements_title' => $this->parseJsonField($record->placements_title, ['main' => 'Placement Partners']),
                    'placements_subtitle' => $record->placements_subtitle ?? '',
                    'placement_images' => $this->parseJsonField($record->placement_images, []),
                    'reserve_title' => $this->parseJsonField($record->reserve_title, ['main' => 'Reserve Your Spot']),
                    'reserve_subtitle' => $record->reserve_subtitle ?? '',
                    // DB columns are reserve_block1/2/3. These may be stored
                    // as JSON strings or simple strings; parseJsonField handles both.
                    'reserve_block1' => $this->parseJsonField($record->reserve_block1, ['0', 'Days']),
                    'reserve_block2' => $this->parseJsonField($record->reserve_block2, ['0', 'Hours']),
                    'reserve_block3' => $this->parseJsonField($record->reserve_block3, ['0', 'Minutes']),
                    'reserve_button_name' => $record->reserve_button_name ?? 'Enroll Now',
                    'reserve_button_link' => $record->reserve_button_link ?? '',
            ];

            return response()->json($formatted)->header('Cache-Control', 'public, max-age=300');
        } catch (\Exception $e) {
            \Log::error('PlacementsReserveController::index - Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while fetching placements reserve data'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $record = CourseDetailsPageContentPlacementsAndReserve::find($id);

            if (!$record) {
                return response()->json(['message' => 'Not Found'], 404);
            }

            return response()->json($record);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the primary fields. Database columns are named reserve_block*
            $request->validate([
                "placements_title" => "nullable|array",
                "placements_subtitle" => "nullable|string",
                "placement_images" => "nullable|array",

                "reserve_title" => "nullable|array",
                "reserve_subtitle" => "nullable|string",
                // frontend sends reserve_block1/2/3 as arrays [value,label]
                "reserve_block1" => "nullable",
                "reserve_block2" => "nullable",
                "reserve_block3" => "nullable",
                "reserve_button_name" => "nullable|string",
                "reserve_button_link" => "nullable|string",
            ]);

            // Build payload matching DB column names. For reserve_block columns
            // they are stored as strings, so if frontend sent arrays
            // encode them as JSON strings before saving.
            $payload = [];
            $payload['placements_title'] = $request->input('placements_title');
            $payload['placements_subtitle'] = $request->input('placements_subtitle');
            $payload['placement_images'] = $request->input('placement_images');

            $payload['reserve_title'] = $request->input('reserve_title');
            $payload['reserve_subtitle'] = $request->input('reserve_subtitle');

            foreach ([1,2,3] as $n) {
                $keyIn = "reserve_block{$n}";
                $val = $request->input($keyIn);
                if (is_array($val)) {
                    $payload[$keyIn] = json_encode($val);
                } elseif (!is_null($val)) {
                    $payload[$keyIn] = $val;
                } else {
                    $payload[$keyIn] = null;
                }
            }

            $payload['reserve_button_name'] = $request->input('reserve_button_name');
            $payload['reserve_button_link'] = $request->input('reserve_button_link');

            // Ensure JSON/array fields are stored as JSON strings to avoid query grammar issues
            foreach (['placements_title','placement_images','reserve_title'] as $k) {
                if (array_key_exists($k, $payload) && is_array($payload[$k])) {
                    $payload[$k] = json_encode($payload[$k]);
                }
            }

            $record = CourseDetailsPageContentPlacementsAndReserve::create($payload);

            return response()->json([
                'message' => 'Record saved successfully',
                'data' => $record
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $item = CourseDetailsPageContentPlacementsAndReserve::findOrFail($id);

            // Map incoming reserve_block* (encode arrays to JSON strings)
            $input = $request->all();
            // Ensure JSON/array fields are encoded to strings for DB columns
            foreach (['placements_title','placement_images','reserve_title'] as $k) {
                if (array_key_exists($k, $input) && is_array($input[$k])) {
                    $input[$k] = json_encode($input[$k]);
                }
            }
            foreach ([1,2,3] as $n) {
                $keyIn = "reserve_block{$n}";
                if (array_key_exists($keyIn, $input)) {
                    $val = $input[$keyIn];
                    if (is_array($val)) {
                        $input[$keyIn] = json_encode($val);
                    } else {
                        $input[$keyIn] = $val;
                    }
                }
            }
            $item->update($input);

            return response()->json([
                'message' => 'Record updated successfully',
                'data' => $item
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        return CourseDetailsPageContentPlacementsAndReserve::destroy($id);
    }

    /**
     * Parse a field that might be JSON string, array, or have a 'main' key
     */
    private function parseField($value, $default = '')
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded) && isset($decoded['main'])) {
                return $decoded['main'];
            }
            return $decoded ?? $value;
        }
        if (is_array($value) && isset($value['main'])) {
            return $value['main'];
        }
        return $value ?? $default;
    }

    /**
     * Parse a JSON field that might be stored as string
     */
    private function parseJsonField($value, $default = [])
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : $default;
        }
        return is_array($value) ? $value : $default;
    }
}
