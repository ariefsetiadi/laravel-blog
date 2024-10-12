<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'thumbnail',
        'content',
        'status',
        'slug',
        'notes',
        'created_by',
        'updated_by',
        'published_by',
        'published_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id', 'category_id');
    }
}
