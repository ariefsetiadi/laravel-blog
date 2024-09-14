<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $primaryKey   =   'session_id';
    protected $keyType      =   'string';
    public $incrementing    =   false;
    public $timestamps      =   false;
    protected $fillable     =   [
        'session_id',
        'country',
        'ip_address',
        'device',
        'platform',
        'browser',
        'total_page',
        'first_active_at',
        'last_active_at',
    ];
}
