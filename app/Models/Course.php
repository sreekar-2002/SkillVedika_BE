<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
        'price',
        'rating',
        'students',
        'category_id',
        'status',
        'mode',
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'students' => 'integer',
    ];

    public function details(): HasOne
    {
        return $this->hasOne(CourseDetail::class);
    }
}
