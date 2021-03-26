<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->country) {
            $post_year = substr($this->post->comparison_date,0,4);
            return [
                'name' => $this->country->name,
                'code' => $this->country->code,
                'currency' => $this->country->currency,
                'product_unit' => $this->product_unit,
                'wage' => new CountryWageResource($this->country->country_wages->where('year', $post_year)->first())
            ];
        }

        return [
            'name' => $this->name,
            'code' => $this->code,
            'currency' => $this->currency
        ];
    }
}
