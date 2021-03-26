<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function country_wages(): HasMany
    {
        return $this->hasMany(CountryWage::class, 'country_id', 'id');
    }
}
