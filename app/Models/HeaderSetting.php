<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderSetting extends Model
{
    protected $table = 'header_settings';

    protected $fillable = [
        'logo',
        'menu_items',
    ];

    protected $casts = [
        'menu_items' => 'array',
    ];
}
