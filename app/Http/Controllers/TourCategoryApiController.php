<?php

namespace App\Http\Controllers;

use App\Http\Resources\TourCategoryApiResource;
use App\Models\CategoryTour;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class TourCategoryApiController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of all TourCategory.
     */
    public function index()
    {
        return TourCategoryApiResource::collection(
            CategoryTour::all()
        );
    }

    /**
     * Display a specific TourCategory by ID.
     */
    public function show($slug)
    {
        // Check if the user exists
        $TourCategory =  CategoryTour::where('slug', $slug)->first();


        // If TourCategory not found, return an error response
        if (!$TourCategory) {
            return $this->error('', 'Tour Category not found', 404);
        }
        return new TourCategoryApiResource($TourCategory);
    }
}
