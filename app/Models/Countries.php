<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Countries extends Model
{
    use HasFactory, FilterQueryString;

    protected $filters = [
        'sort',
        'like',
        'name', 'code', 'currency'
    ];
    protected $table = 'countries';
    protected $fillable = ['name', 'code', 'currency'];
}
