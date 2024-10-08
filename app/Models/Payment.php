<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',  // Cast status as a boolean
    ];


    protected static function booted(): void
    {
        static::deleted(function (Payment $payment) {
            if ($payment->image) {
                Storage::delete("public/{$payment->image}");
            }
        });

        static::updating(function (Payment $payment) {
            $originalImage = $payment->getOriginal('image');
            $newImage = $payment->image;
            // Delete the old image if it has changed and exists
            if ($originalImage !== $newImage && $originalImage) {
                Storage::delete("public/{$originalImage}");
            }
        });
    }

    /**
     * Automatically assign the authenticated user as the creator when creating a new destination.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $payment->created_by = Auth::id();
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}