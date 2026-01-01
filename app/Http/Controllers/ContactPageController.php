<?php

namespace App\Http\Controllers;

use App\Models\ContactPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContactPageController extends Controller
{
    // GET /api/contact-page
    public function index()
    {
        // get the latest record only, do not create defaults
        $content = ContactPageContent::orderBy('id', 'desc')->first();
        if (!$content) {
            // Return empty fields (all null/empty) or 404
            // Option 1: return 404
            // return response()->json(['message' => 'No contact page content found'], 404);

            // Option 2: return all fields as null/empty (preferred for frontend form binding)
            return response()->json([
                'hero_title' => null,
                'hero_description' => null,
                'hero_button' => null,
                'hero_button_link' => null,
                'hero_image' => null,
                'contactus_target' => null,
                'contactus_title' => null,
                'contactus_subtitle' => null,
                'contacts_email_label' => null,
                'contacts_email_id' => null,
                'contacts_email_id_link' => null,
                'contacts_phone_label' => null,
                'contacts_phone_number' => null,
                'contacts_phone_number_link' => null,
                'contactus_location1_label' => null,
                'contactus_location1_address' => null,
                'contactus_location1_address_link' => null,
                'contactus_location2_label' => null,
                'contactus_location2_address' => null,
                'contactus_location2_address_link' => null,
                'map_title' => null,
                'map_subtitle' => null,
                'map_link' => null,
                'map_link_india' => null,
                'demo_target' => null,
                'demo_title' => null,
                'demo_subtitle' => null,
                'demo_points' => [],
            ]);
        }
        return response()->json($content);
    }

    // POST /api/contact-page/update
    public function update(Request $request)
    {
        try {
            // Use latest record. If none exists yet, create a new one so admin can save initial content.
            $content = ContactPageContent::orderBy('id', 'desc')->first();
            if (!$content) {
                $content = new ContactPageContent();
            }

            // Convert empty strings to null for nullable fields to avoid validation issues
            $requestData = $request->all();
            foreach ($requestData as $key => $value) {
                if ($value === '') {
                    $request->merge([$key => null]);
                }
            }

            // Validate general fields (we keep most optional)
            // Note: JSON fields (hero_title, contactus_title, map_title, demo_title, demo_points) are validated separately
            $request->validate([
                'hero_description' => 'nullable|string',
                'hero_button' => 'nullable|string|max:255',
                'hero_button_link' => 'nullable|string|max:255',
                'hero_image' => 'nullable|string',
                'hero_image_remove' => 'nullable|string',

                'contactus_target' => 'nullable|string|max:255',
                'contactus_subtitle' => 'nullable|string',

                'contacts_email_label' => 'nullable|string|max:255',
                'contacts_email_id' => 'nullable|string|max:255',
                'contacts_email_id_link' => 'nullable|string|max:255',

                'contacts_phone_label' => 'nullable|string|max:255',
                'contacts_phone_number' => 'nullable|string|max:255',
                'contacts_phone_number_link' => 'nullable|string|max:255',

                'contactus_location1_label' => 'nullable|string|max:255',
                'contactus_location1_address' => 'nullable|string|max:2000',
                'contactus_location1_address_link' => 'nullable|string|max:2000', // Increased for long Google Maps embed URLs

                'contactus_location2_label' => 'nullable|string|max:255',
                'contactus_location2_address' => 'nullable|string|max:2000',
                'contactus_location2_address_link' => 'nullable|string|max:2000', // Increased for long Google Maps embed URLs

                'map_subtitle' => 'nullable|string',
                'map_link' => 'nullable|string|max:2000', // Increased for long Google Maps embed URLs
                'map_link_india' => 'nullable|string|max:2000', // Increased for long Google Maps embed URLs

                'demo_target' => 'nullable|string|max:255',
                'demo_subtitle' => 'nullable|string',

                // JSON fields - allow array, object, or null (no strict type checking)
                'hero_title' => 'nullable',
                'contactus_title' => 'nullable',
                'map_title' => 'nullable',
                'demo_title' => 'nullable',
                'demo_points' => 'nullable',
            ]);

        // HERO TITLE (JSON) — expected as JSON string or array
        // Use array_key_exists to check if key exists (even if value is null/empty)
        $allRequestData = $request->all();

        if (array_key_exists('hero_title', $allRequestData)) {
            $heroTitle = $this->parseJsonField($request->input('hero_title'));
            $content->hero_title = $heroTitle;
        }

        if (array_key_exists('contactus_title', $allRequestData)) {
            $content->contactus_title = $this->parseJsonField($request->input('contactus_title'));
        }

        if (array_key_exists('map_title', $allRequestData)) {
            $content->map_title = $this->parseJsonField($request->input('map_title'));
        }

        if (array_key_exists('demo_title', $allRequestData)) {
            $content->demo_title = $this->parseJsonField($request->input('demo_title'));
        }

        // demo_points expects array of objects
        if (array_key_exists('demo_points', $allRequestData)) {
            $demoPoints = $this->parseJsonField($request->input('demo_points'));
            if (is_array($demoPoints)) {
                $content->demo_points = $demoPoints;
            }
        }

        // other scalar fields - update all fields that are present in the request
        // Use array_key_exists to check if key exists (even if value is null/empty)
        // Note: $allRequestData was already defined above for JSON fields

        if (array_key_exists('hero_description', $allRequestData)) {
            $content->hero_description = $request->input('hero_description');
        }
        if (array_key_exists('hero_button', $allRequestData)) {
            $content->hero_button = $request->input('hero_button');
        }
        if (array_key_exists('hero_button_link', $allRequestData)) {
            $content->hero_button_link = $request->input('hero_button_link');
        }

        if (array_key_exists('contactus_target', $allRequestData)) {
            $content->contactus_target = $request->input('contactus_target');
        }
        if (array_key_exists('contactus_subtitle', $allRequestData)) {
            $content->contactus_subtitle = $request->input('contactus_subtitle');
        }

        if (array_key_exists('contacts_email_label', $allRequestData)) {
            $content->contacts_email_label = $request->input('contacts_email_label');
        }
        if (array_key_exists('contacts_email_id', $allRequestData)) {
            $content->contacts_email_id = $request->input('contacts_email_id');
        }
        if (array_key_exists('contacts_email_id_link', $allRequestData)) {
            $content->contacts_email_id_link = $request->input('contacts_email_id_link');
        }

        if (array_key_exists('contacts_phone_label', $allRequestData)) {
            $content->contacts_phone_label = $request->input('contacts_phone_label');
        }
        if (array_key_exists('contacts_phone_number', $allRequestData)) {
            $content->contacts_phone_number = $request->input('contacts_phone_number');
        }
        if (array_key_exists('contacts_phone_number_link', $allRequestData)) {
            $content->contacts_phone_number_link = $request->input('contacts_phone_number_link');
        }

        if (array_key_exists('contactus_location1_label', $allRequestData)) {
            $content->contactus_location1_label = $request->input('contactus_location1_label');
        }
        if (array_key_exists('contactus_location1_address', $allRequestData)) {
            $content->contactus_location1_address = $request->input('contactus_location1_address');
        }
        if (array_key_exists('contactus_location1_address_link', $allRequestData)) {
            $content->contactus_location1_address_link = $request->input('contactus_location1_address_link');
        }

        if (array_key_exists('contactus_location2_label', $allRequestData)) {
            $content->contactus_location2_label = $request->input('contactus_location2_label');
        }
        if (array_key_exists('contactus_location2_address', $allRequestData)) {
            $content->contactus_location2_address = $request->input('contactus_location2_address');
        }
        if (array_key_exists('contactus_location2_address_link', $allRequestData)) {
            $content->contactus_location2_address_link = $request->input('contactus_location2_address_link');
        }

        if (array_key_exists('map_subtitle', $allRequestData)) {
            $content->map_subtitle = $request->input('map_subtitle');
        }
        if (array_key_exists('map_link', $allRequestData)) {
            $content->map_link = $request->input('map_link');
        }
        if (array_key_exists('map_link_india', $allRequestData)) {
            $content->map_link_india = $request->input('map_link_india');
        }

        if (array_key_exists('demo_target', $allRequestData)) {
            $content->demo_target = $request->input('demo_target');
        }
        if (array_key_exists('demo_subtitle', $allRequestData)) {
            $content->demo_subtitle = $request->input('demo_subtitle');
        }

        // HERO IMAGE handling
        // - prefer file upload (server-side store)
        // - support `hero_image` as a remote URL (provided by client after cloud upload)
        if ($request->hasFile('hero_image')) {
            $file = $request->file('hero_image');
            $path = $file->storePublicly('uploads/contact', ['disk' => 'public']);
            $content->hero_image = '/storage/' . $path;
        } elseif ($request->filled('hero_image')) {
            // client provided a remote URL (e.g., Cloudinary). Save as-is.
            $content->hero_image = $request->input('hero_image');
        } elseif ($request->filled('hero_image_remove') && $request->input('hero_image_remove') === '1') {
            // optional: remove image
            if ($content->hero_image) {
                // attempt to delete existing
                $existing = Str::after($content->hero_image, '/storage/');
                if ($existing && Storage::disk('public')->exists($existing)) {
                    Storage::disk('public')->delete($existing);
                }
            }
            $content->hero_image = null;
        }

            $content->save();

            // Refresh the model to ensure we return the latest data from database
            $content->refresh();

            return response()->json([
                'message' => 'Contact page updated successfully',
                'content' => $content
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            // Log validation errors for debugging
            Log::error('Contact page validation failed', [
                'errors' => $ve->errors(),
                'request_data' => $request->except(['hero_image']) // Exclude large image data
            ]);

            // Return JSON error for validation failures instead of HTML
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            // Return JSON error for any unexpected exception
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // CREATE (POST) - create first record
    public function store(Request $request)
    {
        $data = $request->validate([
            'hero_title' => 'nullable|array',
            'hero_description' => 'nullable|string',
            'hero_button' => 'nullable|string',
            'hero_button_link' => 'nullable|string',
            'hero_image' => 'nullable|string',

            'contactus_target' => 'nullable|string',
            'contactus_title' => 'nullable|array',
            'contactus_subtitle' => 'nullable|string',

            'contacts_email_label' => 'nullable|string',
            'contacts_email_id' => 'nullable|string',
            'contacts_email_id_link' => 'nullable|string',

            'contacts_phone_label' => 'nullable|string',
            'contacts_phone_number' => 'nullable|string',
            'contacts_phone_number_link' => 'nullable|string',

            'contactus_location1_label' => 'nullable|string',
            'contactus_location1_address' => 'nullable|string',
            'contactus_location1_address_link' => 'nullable|string',

            'contactus_location2_label' => 'nullable|string',
            'contactus_location2_address' => 'nullable|string',
            'contactus_location2_address_link' => 'nullable|string',

            'map_title' => 'nullable|array',
            'map_subtitle' => 'nullable|string',
            'map_link' => 'nullable|string',
            'map_link_india' => 'nullable|string',

            'demo_target' => 'nullable|string',
            'demo_title' => 'nullable|array',
            'demo_subtitle' => 'nullable|string',
            'demo_points' => 'nullable|array',
        ]);

        $record = ContactPageContent::create($data);

        return response()->json(['message' => 'Created', 'data' => $record], 201);
    }

    // DELETE — remove specific or latest contact page content
    public function destroy($id = null)
    {
        $record = $id ? ContactPageContent::find($id) : ContactPageContent::orderBy('id', 'desc')->first();

        if (!$record) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $record->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }

    private function parseJsonField($value)
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
            // fallback: try to interpret as a simple string into text property
            return ['text' => $value];
        }
        return $value;
    }
}
