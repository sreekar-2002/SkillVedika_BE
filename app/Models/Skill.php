<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    // The migration created the table `all_skills` (not the default `skills`).
    // Explicitly set the table name so Eloquent writes to the correct table.
    protected $table = 'all_skills';

    protected $fillable = ['name', 'description', 'category'];
}
