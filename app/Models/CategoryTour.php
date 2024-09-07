<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryTour extends Model
{
    use HasFactory;



    /**
     * The attributes that are mass assignable (fillable).
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'status',
    ];


    protected static function booted(): void
    {
        static::deleted(function (CategoryTour $categoryTour) {
            if ($categoryTour->image) {
                Storage::delete("public/{$categoryTour->image}");
            }
        });

        static::updating(function (CategoryTour $categoryTour) {
            $originalImage = $categoryTour->getOriginal('image');
            $newImage = $categoryTour->image;
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
