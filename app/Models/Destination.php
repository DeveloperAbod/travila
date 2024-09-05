<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Destination extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable (fillable).
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'language',
        'country',
        'timezone',
        'currency',
        'peak_season',
        'images',
        'google_map_url',
        'description',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'peak_season' => 'json',  // Casting peak_season to json
        'images' => 'json'  // Casting images to json
    ];


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Automatically assign the authenticated user as the creator when creating a new destination.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($destination) {
            $destination->created_by = Auth::id();
        });
    }

    protected static function booted(): void
    {

            static::deleted(function (Destination $destination) {
                  if ($destination->images) {
                foreach ($destination->images as $image) {
                    Storage::delete("public/$image");
                }
                 }
            });


        static::updating(function (Destination $destination) {

            if ($destination->images) {
                // get different between the updated list and database list
                $imagesToDelete = array_diff($destination->getOriginal('images'), $destination->images);
                foreach ($imagesToDelete as $image) {
                    Storage::delete("public/$image");
                }
            }
        });
    }
}
