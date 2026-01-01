<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularTag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'usage_count'];
}
