<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrFaq extends Model
{
    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
        'show' => 'boolean',
    ];
}

