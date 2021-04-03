<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function App\Helpers\calculate_purchasing_power;
use function App\Helpers\wage_type_decode;

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
                'product_type' => $this->product_type,
                'wage' => $this->current_wage_info['wage'],
                'minimum_wage_percentage' => $this->current_wage_info['minimum_wage_percentage'],
                'wage_type' => wage_type_decode($this->current_wage_info['wage_type']),
                'purchasing_power' => calculate_purchasing_power($this->product_unit, $this->current_wage_info['wage'])
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
