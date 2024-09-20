<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'description',
        'phone',
        'email',
        'social_media',
        'address',
        'created_by',
        'updated_by',
    ];
}
