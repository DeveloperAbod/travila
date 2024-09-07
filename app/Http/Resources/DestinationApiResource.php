<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'name' => $this->name,
                'slug' => $this->slug,
                'language' => $this->language,
                'country' => $this->country,
                'timezone' => $this->timezone,
                'currency' => $this->currency,
                'peak_season' => $this->peak_season,
                'images' => $this->images,
                'google_map_url' => $this->google_map_url,
                'description' => $this->description,
                'created_at' => $this->created_at,
            ],
            'relationships' => [
                'created_by' => $this->creator,
            ]
        ];
    }
}