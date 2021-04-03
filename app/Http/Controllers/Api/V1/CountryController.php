<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryCollection;
use App\Models\Countries;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * @param Request $request
     * @return CountryCollection
     */
    public function index(Request $request): CountryCollection
    {
        $paginate = $request->get('limit') ?: 15;
        $countries = Countries::filter()->paginate($paginate)->appends(request()->except('page'));
        return new CountryCollection($countries);
    }
}
