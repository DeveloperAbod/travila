<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourCategoryApiResource extends JsonResource
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
                'image' => $this->image,
                'description' => $this->description,
                'status' => $this->status,
                'created_at' => $this->created_at,
            ],
            'relationships' => [
                'created_by' => $this->creator,
            ]
        ];
    }
}