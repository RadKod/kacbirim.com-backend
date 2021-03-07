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
            return [
                'name' => $this->country->name,
                'code' => $this->country->code,
                'currency' => $this->country->currency,
                'wage' => $this->minimum_wage,
            ];
        }

        return [
            'name' => $this->name,
            'code' => $this->code,
            'currency' => $this->currency
        ];
    }
}
