<?php

namespace App\Http\Controllers;

use App\Http\Resources\DestinationApiResource;
use App\Models\Destination;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class DestinationApiController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of all destinations.
     */
    public function index()
    {
        return DestinationApiResource::collection(
            Destination::all()
        );
    }

    /**
     * Display a specific destination by ID.
     */
    public function show($slug)
    {
        // Check if the user exists
        $destination =  Destination::where('slug', $slug)->first();


        // If destination not found, return an error response
        if (!$destination) {
            return $this->error('', 'Destination not found', 404);
        }
        return new DestinationApiResource($destination);
    }
}
