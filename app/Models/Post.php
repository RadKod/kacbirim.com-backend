<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Post extends Model
{
    use HasFactory, HasApiTokens, FilterQueryString;

    protected $filters = [
        'sort',
        'greater',
        'greater_or_equal',
        'less',
        'less_or_equal',
        'between',
        'not_between',
        'in',
        'like',
        'tags_in', 'products_countries_in', 'tags_like', 'products_countries_like',
        'id', 'title', 'slug', 'description', 'image', 'comparison_date'
    ];
    protected $fillable = [
        'title', 'slug', 'description', 'image', 'comparison_date'
    ];

    public function tags_like($query, $value)
    {
        [$field, $value] = $this->get_field_and_values($value);
        if (!$value) return $query;

        return $query->whereHas('tags', function ($q) use ($field, $exploded) {
            $q->whereHas('tag', function ($q) use ($field, $exploded) {
                $q->where($field, 'LIKE', '%' . $exploded[0] . '%');
            });
        });
    }

    public function products_countries_like($query, $value)
    {
        [$field, $value] = $this->get_field_and_values($value);
        if (!$value) return $query;

        return $query->whereHas('countries', function ($q) use ($field, $exploded) {
            if ($field === 'product_unit' || $field === 'product_name' || $field === 'product_type') {
                $q->where($field, 'LIKE', '%' . $exploded[0] . '%');
            } else {
                $q->whereHas('country', function ($q) use ($field, $exploded) {
                    if ($field === 'wage') {
                        $q->whereHas('country_wages', function ($q) use ($field, $exploded) {
                            $q->where($field, 'LIKE', '%' . $exploded[0] . '%');
                        });
                    } else {
                        $q->where($field, 'LIKE', '%' . $exploded[0] . '%');
                    }
                });
            }
        });
    }

    public function tags_in($query, $value)
    {
        [$field, $value] = $this->get_field_and_values($value);
        if (!$value) return $query;

        return $query->whereHas('tags', function ($q) use ($field, $exploded) {
            $q->whereHas('tag', function ($q) use ($field, $exploded) {
                $q->whereIn($field, $exploded);
            });
        });
    }

    public function products_countries_in($query, $value)
    {

        [$field, $value] = $this->get_field_and_values($value);
        if (!$value) return $query;

        return $query->whereHas('countries', function ($q) use ($field, $exploded) {
            if ($field === 'product_unit' || $field === 'product_name' || $field === 'product_type') {
                $q->whereIn($field, $exploded);
            } else {
                $q->whereHas('country', function ($q) use ($field, $exploded) {
                    if ($field === 'wage') {
                        $q->whereHas('country_wages', function ($q) use ($field, $exploded) {
                            $q->whereIn($field, $exploded);
                        });
                    } else {
                        $q->whereIn($field, $exploded);
                    }
                });
            }
        });
    }
    
    protected function get_field_and_values($value) {
        $exploded = explode(',', $value);
        $field = array_shift($exploded);
        
        return [$field, (key_exists(0, $exploded) ? $exploded[0] : null)];
    }

    public function countries(): HasMany
    {
        return $this->hasMany(PostCountry::class, 'post_id', 'id');
    }

    public function tags(): HasMany
    {
        return $this->hasMany(PostTag::class, 'post_id', 'id');
    }
}
