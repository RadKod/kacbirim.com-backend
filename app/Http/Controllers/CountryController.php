<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request){
        $countries = Countries::query()->paginate(10);
        return view('country.index', compact('countries'));
    }
}
