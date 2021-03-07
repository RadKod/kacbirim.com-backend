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
        'tags_in', 'countries_in', 'tags_like', 'countries_like',
        'id', 'title', 'slug', 'description', 'image', 'comparison_date', 'unit'
    ];
    protected $fillable = [
        'title', 'slug', 'description', 'image', 'comparison_date', 'unit'
    ];

    public function tags_like($query, $value)
    {

        $exploded = explode(',', $value);
        $field = array_shift($exploded);

        return $query->whereHas('tags', function ($q) use ($field, $exploded) {
            $q->whereHas('tag', function ($q) use ($field, $exploded) {
                $q->where($field, 'LIKE', '%' . $exploded[0] . '%');
            });
        });
    }

    public function countries_like($query, $value)
    {

        $exploded = explode(',', $value);
        $field = array_shift($exploded);

        return $query->whereHas('countries', function ($q) use ($field, $exploded) {
            if ($field === 'wage') {
                $q->where('minimum_wage', 'LIKE', '%' . $exploded[0] . '%');
            } else {
                $q->whereHas('country', function ($q) use ($field, $exploded) {
                    $q->where($field, 'LIKE', '%' . $exploded[0] . '%');
                });
            }
        });
    }

    public function tags_in($query, $value)
    {

        $exploded = explode(',', $value);
        $field = array_shift($exploded);

        return $query->whereHas('tags', function ($q) use ($field, $exploded) {
            $q->whereHas('tag', function ($q) use ($field, $exploded) {
                $q->whereIn($field, $exploded);
            });
        });
    }

    public function countries_in($query, $value)
    {

        $exploded = explode(',', $value);
        $field = array_shift($exploded);

        return $query->whereHas('countries', function ($q) use ($field, $exploded) {
            if ($field === 'wage') {
                $q->whereIn('minimum_wage', $exploded);
            } else {
                $q->whereHas('country', function ($q) use ($field, $exploded) {
                    $q->whereIn($field, $exploded);
                });
            }
        });
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
