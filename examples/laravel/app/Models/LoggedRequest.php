<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \App\Models\LoggedRequest create(array $attributes)
 */
class LoggedRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request',
    ];

    protected $casts = [
        'request' => 'json',
    ];
}
