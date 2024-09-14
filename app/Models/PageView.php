<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    public $timestamps      =   false;
    protected $fillable     =   [
        'session_id',
        'article_id',
        'view_at',
    ];
}
