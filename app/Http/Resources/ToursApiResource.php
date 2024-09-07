<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToursApiResource extends JsonResource
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
                'images' => $this->images,
                'duration' => $this->duration,
                'price' => $this->price,
                'group_size' => $this->group_size,
                'language' => $this->language,
                'overview' => $this->overview,
                'duration_details' => $this->duration_details,
                'created_at' => $this->created_at,
            ],
            'relationships' => [
                'created_by' => $this->creator,
                'categoryTour' => $this->categoryTour,
                'destinations' => $this->destinations,
            ]
        ];
    }
}