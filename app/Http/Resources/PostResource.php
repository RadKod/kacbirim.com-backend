<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => asset('storage/' . $this->image),
            'comparison_date' => $this->comparison_date,
            'tags' => TagResource::collection($this->tags),
            'products_countries' => CountryResource::collection($this->countries)
        ];
    }
}
