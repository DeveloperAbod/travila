<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutUsApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'attributes' => [
                'email' => $this->email,
                'google_map_url' => $this->google_map_url,
                'call_number' => $this->call_number,
                'whats_number' => $this->whats_number,
            ]
        ];
    }
}
