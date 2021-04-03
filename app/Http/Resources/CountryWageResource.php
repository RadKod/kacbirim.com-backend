<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function App\Helpers\wage_type_decode;

class CountryWageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'year' => $this->year,
            'wage' => $this->wage,
            'minimum_wage_percentage' => $this->minimum_wage_percentage,
            'wage_type' => wage_type_decode($this->wage_type)
        ];
    }
}
