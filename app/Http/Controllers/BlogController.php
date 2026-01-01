<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class BlogController extends Controller
{
    /**
     * List blogs with optional filters (search, categories, recent)
     */
    public function index(Request $request)
    {
        $query = Blog::with('category');

        // Only show published by default (unless status=all or specific status provided)
        if ($request->filled('status')) {
            $status = $request->input('status');
            // If status is 'all', don't filter by status
            if ($status !== 'all' && Schema::hasColumn('blogs', 'status')) {
                // Support comma-separated statuses or single status
                if (is_string($status) && strpos($status, ',') !== false) {
                    $statuses = array_map('trim', explode(',', $status));
                    $query->whereIn('status', $statuses);
                } else {
                    $query->where('status', $status);
                }
            }
        } else {
            // Default: only show published
            if (Schema::hasColumn('blogs', 'status')) {
                $query->where('status', 'published');
            }
        }

        // simple category filter
        if ($request->filled('categories')) {
            $cats = $request->categories;
            if (!is_array($cats)) {
                $cats = is_string($cats) && strpos($cats, ',') !== false ? explode(',', $cats) : [$cats];
            }
            $cats = array_values(array_filter(array_map(function ($c) { return is_numeric($c) ? intval($c) : $c; }, $cats)));
            if (count($cats) > 0) {
                $query->whereIn('category_id', $cats);
            }
        }

        // keyword search (try a few likely columns)
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                if (Schema::hasColumn('blogs', 'blog_name')) {
                    $q->where('blog_name', 'LIKE', "%{$keyword}%");
                }
                if (Schema::hasColumn('blogs', 'short_description')) {
                    $q->orWhere('short_description', 'LIKE', "%{$keyword}%");
                }
                if (Schema::hasColumn('blogs', 'blog_content')) {
                    $q->orWhere('blog_content', 'LIKE', "%{$keyword}%");
                }
            });
        }

        // order — prefer published_at if present
        if (Schema::hasColumn('blogs', 'published_at')) {
            $query->orderBy('published_at', 'desc');
        } else {
            $query->orderBy('blog_id', 'desc');
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'blog_name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'in:draft,published,archived',
            'recent_blog' => 'in:YES,NO'
        ]);

        // Generate unique slug with timestamp to avoid duplicates
        $baseSlug = Str::slug($request->blog_name);
        $slug = $baseSlug;
        $counter = 1;
        while (Blog::where('url_friendly_title', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $blog = Blog::create([
            'blog_name'          => $request->blog_name,
            'url_friendly_title' => $slug,
            'category_id'        => $request->category_id,
            'banner_image'       => $request->banner_image,
            'thumbnail_image'    => $request->thumbnail_image,
            'short_description'  => $request->short_description,
            'blog_content'       => $request->blog_content,
            'published_by'       => $request->published_by,
            'published_at'       => $request->published_at,
            'status'             => $request->status,
            'recent_blog'        => $request->recent_blog,
            'meta_title'         => $request->meta_title,
            'meta_description'   => $request->meta_description,
            'meta_keywords'      => $request->meta_keywords,
            'extra'              => $request->extra,
        ]);

        return response()->json([
            'message' => 'Blog created successfully',
            'blog' => $blog->load('category')
        ], 201);
    }

    public function show($id)
    {
        // If numeric id provided, fetch by primary key
        if (is_numeric($id)) {
            return Blog::with('category')->findOrFail($id);
        }

        // Otherwise treat $id as slug/URL-friendly title and try common fields
        $post = Blog::with('category')->where('url_friendly_title', $id)->first();
        if (!$post && Schema::hasColumn('blogs', 'slug')) {
            $post = Blog::with('category')->where('slug', $id)->first();
        }

        if (!$post) {
            abort(404, 'Not found');
        }

        return $post;
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        // If blog_name is being updated, regenerate slug with uniqueness check
        if ($request->has('blog_name') && $request->blog_name !== $blog->blog_name) {
            $baseSlug = Str::slug($request->blog_name);
            $slug = $baseSlug;
            $counter = 1;
            while (Blog::where('url_friendly_title', $slug)->where('blog_id', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $request->merge(['url_friendly_title' => $slug]);
        }

        $blog->update($request->all());

        return response()->json([
            'message' => 'Blog updated successfully',
            'blog' => $blog->load('category')
        ]);
    }

    public function destroy($id)
    {
        Blog::destroy($id);

        return response()->json([
            'message' => 'Blog deleted successfully'
        ]);
    }
}
