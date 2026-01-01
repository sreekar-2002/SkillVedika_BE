<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $primaryKey = "blog_id";

    protected $fillable = [
        'blog_name',
        'url_friendly_title',
        'category_id',
        'banner_image',
        'thumbnail_image',
        'short_description',
        'blog_content',
        'published_by',
        'published_at',
        'status',
        'recent_blog',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'extra',
    ];

    protected $casts = [
        'extra' => 'array',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
