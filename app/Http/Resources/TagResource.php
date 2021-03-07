<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->tag){
            return [
                'name' => $this->tag->name,
                'slug' => $this->tag->slug,
            ];
        }
        return [
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
