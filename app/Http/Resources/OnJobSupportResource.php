<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OnJobSupportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            // HERO SECTION
            'hero_title'            => $this->hero_title,
            'hero_description'      => $this->hero_description,
            'hero_button_text'      => $this->hero_button_text,
            'hero_button_link'      => $this->hero_button_link,
            'hero_image'            => $this->hero_image,

            // REAL-TIME HELP
            'realtime_title'                => $this->realtime_title,
            'realtime_subheading'           => $this->realtime_subheading,
            'realtime_description'          => $this->realtime_description,
            'realtime_subsection_title1'    => $this->realtime_subsection_title1,
            'subsection_title1_description' => $this->subsection_title1_description,
            'realtime_subsection_title2'    => $this->realtime_subsection_title2,
            'subsection_title2_description' => $this->subsection_title2_description,
            'realtime_image'                => $this->realtime_image,

            // WHO IS THIS FOR
            'who_target'    => $this->who_target,
            'who_title'     => $this->who_title,
            'who_subtitle'  => $this->who_subtitle,
            'who_cards'     => $this->who_cards,

            // HOW WE HELP
            'how_title'     => $this->how_title,
            'how_subtitle'  => $this->how_subtitle,
            'how_points'    => $this->how_points,
            'how_footer'    => $this->how_footer,

            // PROCESS
            'process_title'     => $this->process_title,
            'process_subtitle'  => $this->process_subtitle,
            'process_points'    => $this->process_points,

            // WHY CHOOSE
            'why_title'     => $this->why_title,
            'why_points'    => $this->why_points,
            'why_image'     => $this->why_image,

            // READY TO EMPOWER
            'ready_title'       => $this->ready_title,
            'ready_description' => $this->ready_description,
            'ready_button'      => $this->ready_button,
            'ready_button_link' => $this->ready_button_link,
            'ready_image'       => $this->ready_image,

            // DEMO SECTION
            'demo_target'    => $this->demo_target,
            'demo_title'     => $this->demo_title,
            'demo_subtitle'  => $this->demo_subtitle,
            'demo_points'    => $this->demo_points,

            // SEO / Meta fields (allow admin to set explicit SEO values)
            'meta_title'        => $this->meta_title ?? $this->seo_title ?? $this->hero_title,
            'meta_description'  => $this->meta_description ?? $this->seo_description ?? $this->hero_description,
            // meta_keywords may be stored as JSON/array or comma-separated string
            'meta_keywords'     => $this->formatKeywords($this->meta_keywords),

            'created_at'     => $this->created_at,
        ];
    }

    /**
     * Normalize keywords into an array when possible.
     * Accepts arrays, JSON strings, or comma/newline-separated text.
     */
    private function formatKeywords($raw)
    {
        if (is_null($raw) || $raw === '') {
            return null;
        }

        if (is_array($raw)) {
            return array_values(array_filter(array_map('trim', $raw)));
        }

        if (is_string($raw)) {
            $s = trim($raw);

            // Try JSON decode first
            $decoded = json_decode($s, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values(array_filter(array_map('trim', $decoded)));
            }

            // Fallback: split on commas or newlines
            $parts = preg_split('/[,\n]+/', $s);
            return array_values(array_filter(array_map('trim', $parts)));
        }

        return null;
    }
}

