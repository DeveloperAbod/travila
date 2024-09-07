<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'images',
        'duration',
        'price',
        'group_size',
        'language',
        'overview',
        'duration_details',
        'category_tour_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'images' => 'json'  // Casting images to json
    ];

    protected static function booted(): void
    {

        static::deleted(function (Tour $tour) {
            if ($tour->images) {
                foreach ($tour->images as $image) {
                    Storage::delete("public/$image");
                }
            }
        });


        static::updating(function (Tour $tour) {

            if ($tour->images) {
                // get different between the updated list and database list
                $imagesToDelete = array_diff($tour->getOriginal('images'), $tour->images);
                foreach ($imagesToDelete as $image) {
                    Storage::delete("public/$image");
                }
            }
        });
    }



    public function categoryTour()
    {
        return $this->belongsTo(CategoryTour::class);
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class);
    }

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
