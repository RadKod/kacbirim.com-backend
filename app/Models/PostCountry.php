<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCountry extends Model
{
    use HasFactory;

    protected $table = 'post_country';
    protected $fillable = [
        'post_id', 'country_id', 'product_name', 'product_unit', 'product_type'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function getCurrentWageInfoAttribute(): array
    {
        $return_wage = [
            'wage' => '',
            'minimum_wage_percentage' => '',
            'wage_type' => ''
        ];
        $post_year = substr($this->post->comparison_date, 0, 4);
        $wage = $this->country->country_wages->firstWhere('year', $post_year);
        if ($wage) {
            $return_wage['wage'] = $wage->wage;
            $return_wage['minimum_wage_percentage'] = $wage->minimum_wage_percentage;
            $return_wage['wage_type'] = $wage->wage_type;
        }
        return $return_wage;
    }
}
