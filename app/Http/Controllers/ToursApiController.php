<?php

namespace App\Http\Controllers;

use App\Http\Resources\ToursApiResource;
use App\Models\Tour;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class ToursApiController extends Controller
{
    use HttpResponses;


    /**
     * Display a listing of all tours.
     */
    public function index()
    {
        return ToursApiResource::collection(
            Tour::all()
        );
    }

    /**
     * Display a specific tour by ID.
     */
    public function show($slug)
    {
        // Check if the user exists
        $tour =  Tour::where('slug', $slug)->first();


        // If tour not found, return an error response
        if (!$tour) {
            return $this->error('', 'Tour not found', 404);
        }
        return new ToursApiResource($tour);
    }
}
