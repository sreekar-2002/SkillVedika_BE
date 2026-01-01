<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function get()
    {
        $s = Setting::orderBy('id', 'desc')->first() ?? new Setting();

        // Get admin details from authenticated admin or admins table
        $admin = auth('sanctum')->user() ?? \App\Models\Admin::first();
        $adminName = $admin?->name ?? '';
        $adminEmail = $admin?->email ?? '';

        return response()->json([
            // Admin details come from admins table, NOT settings
            "admin_name"        => $adminName,
            "name"              => $adminName,
            "admin_email"       => $adminEmail,
            "email"             => $s->email,  // support email from settings

            "website_title"     => $s->website_title,
            "website_url"       => $s->website_url,
            "google_analytics"  => $s->google_analytics,
            "video_url"         => $s->video_url,

            "phone"             => $s->phone,
            // support_email may be stored in `email` column historically
            "support_email"     => $s->email,

            // support both `location_1` (db) and `location1` (frontend)
            "location1"         => $s->location_1,
            "location2"         => $s->location_2,

            "footer_description" => $s->footer_description,
            // return the canonical key the frontend expects
            "copyright_text"     => $s->copyright,

            "facebook_url"      => $s->facebook_url,
            "instagram_url"     => $s->instagram_url,
            "linkedin_url"      => $s->linkedin_url,
            "youtube_url"       => $s->youtube_url,

            // Full URLs for preview
            // If the stored value is already an absolute URL (e.g. Cloudinary), return it as-is.
            "header_logo_url"   => $s->header_logo ? (preg_match('/^https?:\/\//i', $s->header_logo) ? $s->header_logo : asset('storage/' . $s->header_logo)) : null,
            "footer_logo_url"   => $s->footer_logo ? (preg_match('/^https?:\/\//i', $s->footer_logo) ? $s->footer_logo : asset('storage/' . $s->footer_logo)) : null,
            "course_banner_url" => $s->course_banner ? (preg_match('/^https?:\/\//i', $s->course_banner) ? $s->course_banner : asset('storage/' . $s->course_banner)) : null,
            "blog_banner_url"   => $s->blog_banner ? (preg_match('/^https?:\/\//i', $s->blog_banner) ? $s->blog_banner : asset('storage/' . $s->blog_banner)) : null,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $settings = Setting::orderBy('id', 'desc')->first() ?? new Setting();

            // Use safe default values (empty string) when creating a new Setting
            // to avoid inserting NULL into non-nullable columns.
            // If a value exists on the model, prefer that; otherwise default to ''.

            $settings->website_title = $request->input('website_title', $settings->website_title ?? '');
            $settings->website_url = $request->input('website_url', $settings->website_url ?? '');
            $settings->google_analytics = $request->input('google_analytics', $settings->google_analytics ?? '');
            $settings->video_url = $request->input('video_url', $settings->video_url ?? '');

            $settings->phone = $request->input('phone', $settings->phone ?? '');
            // Accept both support_email (frontend) and email (legacy)
            $settings->email = $request->input('support_email', $request->input('email', $settings->email ?? ''));

            // Accept both camelCase (location1) and snake_case (location_1)
            $settings->location_1 = $request->input('location1', $request->input('location_1', $settings->location_1 ?? ''));
            $settings->location_2 = $request->input('location2', $request->input('location_2', $settings->location_2 ?? ''));

            $settings->footer_description = $request->input('footer_description', $settings->footer_description ?? '');
            // Accept copyright_text from frontend, fall back to copyright
            $settings->copyright = $request->input('copyright_text', $request->input('copyright', $settings->copyright ?? ''));

            $settings->facebook_url = $request->input('facebook_url', $settings->facebook_url ?? '');
            $settings->instagram_url = $request->input('instagram_url', $settings->instagram_url ?? '');
            $settings->linkedin_url = $request->input('linkedin_url', $settings->linkedin_url ?? '');
            $settings->youtube_url = $request->input('youtube_url', $settings->youtube_url ?? '');

            // FILE UPLOADS (multipart/form-data) -> store locally
            if ($request->hasFile('header_logo')) {
                $settings->header_logo = $request->file('header_logo')->store('settings', 'public');
            }

            if ($request->hasFile('footer_logo')) {
                $settings->footer_logo = $request->file('footer_logo')->store('settings', 'public');
            }

            if ($request->hasFile('course_banner')) {
                $settings->course_banner = $request->file('course_banner')->store('settings', 'public');
            }

            if ($request->hasFile('blog_banner')) {
                $settings->blog_banner = $request->file('blog_banner')->store('settings', 'public');
            }

            // Alternatively frontend may send remote URLs (Cloudinary). Accept those too.
            if ($request->filled('header_logo_url')) {
                $settings->header_logo = $request->input('header_logo_url');
            }
            if ($request->filled('footer_logo_url')) {
                $settings->footer_logo = $request->input('footer_logo_url');
            }
            if ($request->filled('course_banner_url')) {
                $settings->course_banner = $request->input('course_banner_url');
            }
            if ($request->filled('blog_banner_url')) {
                $settings->blog_banner = $request->input('blog_banner_url');
            }

            $settings->save();

            // Return helpful URLs for the frontend if files were uploaded
            $response = [
                'status' => true,
                'message' => 'Settings updated successfully!',
                'data' => $settings,
            ];

            if ($settings->header_logo) $response['header_logo_url'] = preg_match('/^https?:\/\//i', $settings->header_logo) ? $settings->header_logo : asset('storage/' . $settings->header_logo);
            if ($settings->footer_logo) $response['footer_logo_url'] = preg_match('/^https?:\/\//i', $settings->footer_logo) ? $settings->footer_logo : asset('storage/' . $settings->footer_logo);
            if ($settings->course_banner) $response['course_banner_url'] = preg_match('/^https?:\/\//i', $settings->course_banner) ? $settings->course_banner : asset('storage/' . $settings->course_banner);
            if ($settings->blog_banner) $response['blog_banner_url'] = preg_match('/^https?:\/\//i', $settings->blog_banner) ? $settings->blog_banner : asset('storage/' . $settings->blog_banner);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Log and return JSON for easier frontend debugging
            Log::error('Settings update failed: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => false,
                'message' => 'Settings update failed: ' . $e->getMessage(),
            ], 500);
        }
    }

}
