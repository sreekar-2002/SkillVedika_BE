<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $primaryKey = "category_id";

    protected $fillable = [
        'category_name',
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, "category_id", "category_id");
    }
}
