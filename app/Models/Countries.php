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
    protected $appends = ['current_wage'];
    protected $hidden = ['country_wages', 'created_at', 'updated_at'];

    public function country_wages(): HasMany
    {
        return $this->hasMany(CountryWage::class, 'country_id', 'id');
    }

    public function getCurrentWageAttribute(): ?int
    {
        $return_wage = null;
        $post_year = substr(date('Y'), 0, 4);
        $wage = $this->country_wages->firstWhere('year', $post_year);
        if ($wage) {
            $return_wage = $wage->wage;
        }
        return $return_wage;
    }
}
