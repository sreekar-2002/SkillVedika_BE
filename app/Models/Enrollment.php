<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollments';

    protected $guarded = [];

    protected $casts = [
        'courses' => 'array',
        'meta' => 'array',
        'contacted_on' => 'datetime',
    ];
}
