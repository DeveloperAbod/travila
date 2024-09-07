<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable (fillable).
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'google_map_url',
        'call_number',
        'whats_number',
    ];
}
