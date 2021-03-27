<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function App\Helpers\calculate_purchasing_power;

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
                'product_name' => $this->product_name,
                'product_unit' => $this->product_unit,
                'wage' => $this->current_wage,
                'purchasing_power' => calculate_purchasing_power($this->product_unit, $this->current_wage),
            ];
        }

        return [
            'name' => $this->name,
            'code' => $this->code,
            'currency' => $this->currency,
            'wages' => CountryWageResource::collection($this->country_wages)
        ];
    }
}
