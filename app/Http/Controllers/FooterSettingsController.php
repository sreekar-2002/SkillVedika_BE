<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\FooterSetting;

class FooterSettingsController extends Controller
{
    /**
     * Get footer settings
     */
    public function index()
    {
        $settings = FooterSetting::orderBy('id', 'desc')->first();

        // If not created yet — create an empty one
        if (!$settings) {
            $settings = FooterSetting::create([
                'get_in_touch' => '',
                'email_placeholder' => '',
                'logo' => '',
                'about' => '',
                'explore' => 'Explore',
                'explore_links' => [],
                'support' => 'Support',
                'support_links' => [],
                'contact' => 'Contact',
                'contact_details' => [
                    'email' => '',
                    'phone' => '',
                    'locations' => [],
                ],
                'follow_us' => 'Follow Us',
                'social_media_icons' => [],
                'social_links' => [
                    'whatsapp' => '',
                    'instagram' => '',
                    'twitter' => '',
                    'youtube' => '',
                    'facebook' => '',
                ],
                'copyright' => '',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Update footer settings
     */
    public function update(Request $request)
    {
    try {
        Log::info('FooterSettings update request', $request->all());

        $validated = $request->validate([
            'get_in_touch'        => 'nullable|string',
            'email_placeholder'   => 'nullable|string',
            'logo'                => 'nullable|string',
            'about'               => 'nullable|string',

            'explore'             => 'nullable|string',
            'explore_links'       => 'nullable|array',
            'explore_links.*.text' => 'required_with:explore_links|string',
            'explore_links.*.slug' => 'required_with:explore_links|string',

            'support'             => 'nullable|string',
            'support_links'       => 'nullable|array',
            'support_links.*.text' => 'required_with:support_links|string',
            'support_links.*.slug' => 'required_with:support_links|string',

            'contact'             => 'nullable|string',
            'contact_details'     => 'nullable|array',
            'contact_details.email' => 'nullable|string',
            'contact_details.phone' => 'nullable|string',
            'contact_details.locations' => 'nullable|array',
            'contact_details.locations.*' => 'nullable|string',

            'follow_us'           => 'nullable|string',
            'social_media_icons'  => 'nullable|array',

            'social_links'        => 'nullable|array',
            'social_links.whatsapp' => 'nullable|string',
            'social_links.instagram' => 'nullable|string',
            'social_links.twitter' => 'nullable|string',
            'social_links.youtube' => 'nullable|string',
            'social_links.facebook' => 'nullable|string',

            'copyright'           => 'nullable|string',
        ]);

            $settings = FooterSetting::orderBy('id', 'desc')->first();

            if (!$settings) {
                $settings = new FooterSetting();
            }

            $settings->fill($validated);
            $settings->save();

            Log::info('FooterSettings saved', $settings->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Footer settings updated successfully!',
                'data' => $settings
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('FooterSettings validation failed', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('FooterSettings update exception: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }

    // GET /api/footer-settings/{id}
    public function show($id)
    {
        $settings = FooterSetting::find($id);

        if (!$settings) {
            return response()->json(['message' => 'Footer settings not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    // POST /api/footer-settings (create new)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'get_in_touch' => 'nullable|string',
            'email_placeholder' => 'nullable|string',
            'logo' => 'nullable|string',
            'about' => 'nullable|string',
            'explore' => 'nullable|string',
            'explore_links' => 'nullable|array',
            'support' => 'nullable|string',
            'support_links' => 'nullable|array',
            'contact' => 'nullable|string',
            'contact_details' => 'nullable|array',
            'follow_us' => 'nullable|string',
            'social_media_icons' => 'nullable|array',
            'social_links' => 'nullable|array',
            'copyright' => 'nullable|string',
        ]);

        $settings = FooterSetting::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Footer settings created successfully',
            'data' => $settings
        ], 201);
    }

    // DELETE /api/footer-settings/{id}
    public function destroy($id)
    {
        $settings = FooterSetting::find($id);

        if (!$settings) {
            return response()->json(['message' => 'Footer settings not found'], 404);
        }

        $settings->delete();

        return response()->json([
            'success' => true,
            'message' => 'Footer settings deleted successfully'
        ]);
    }
}
