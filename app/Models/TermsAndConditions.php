<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndConditions extends Model
{
    use HasFactory;

    protected $table = "terms_and_conditions";

    protected $fillable = [
        'title',
        'content',
        'last_updated_on',
    ];

    protected $casts = [
        'last_updated_on' => 'datetime',
    ];
}
