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
        'post_id', 'country_id', 'product_name', 'product_unit'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function getCurrentWageAttribute(): ?int
    {
        $return_wage = null;
        $post_year = substr($this->post->comparison_date, 0, 4);
        $wage = $this->country->country_wages->firstWhere('year', $post_year);
        if ($wage) {
            $return_wage = $wage->wage;
        }
        return $return_wage;
    }
}
