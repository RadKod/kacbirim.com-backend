<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryCollection;
use App\Models\Countries;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * @return CountryCollection
     */
    public function index(): CountryCollection
    {
        $posts = Countries::filter()->paginate()->appends(request()->except('page'));
        return new CountryCollection($posts);
    }
}
