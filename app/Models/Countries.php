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
        'name', 'code', 'currency', 'currency_id'
    ];
    protected $table = 'countries';
    protected $fillable = ['name', 'code', 'currency', 'currency_id'];
    protected $appends = ['current_wage'];
    protected $hidden = ['country_wages', 'created_at', 'updated_at'];

    public function country_wages(): HasMany
    {
        return $this->hasMany(CountryWage::class, 'country_id', 'id');
    }

    public function getCurrentWageAttribute(): array
    {
        $post_year = substr(date('Y'), 0, 4);
        $return_wage = [
            'wage' => '',
            'minimum_wage_percentage' => '',
            'wage_type' => ''
        ];
        $wage = $this->country_wages->firstWhere('year', $post_year);
        if ($wage) {
            $return_wage['wage'] = $wage->wage;
            $return_wage['minimum_wage_percentage'] = $wage->minimum_wage_percentage;
            $return_wage['wage_type'] = $wage->wage_type;
        }
        return $return_wage;
    }
}
