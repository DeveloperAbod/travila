<?php

namespace App\Http\Controllers;

use App\Http\Resources\AboutUsApiResource;
use App\Models\AboutUs;
use App\Traits\HttpResponses;

use Illuminate\Http\Request;

class AboutUsApiController extends Controller
{
    use HttpResponses;

    public function index()
    {
        // Check if the user exists
        $aboutUs =  AboutUs::find(1);

        if (!$aboutUs) {
            return $this->error('', 'about us not found', 404);
        }
        return new AboutUsApiResource($aboutUs);
    }
}
