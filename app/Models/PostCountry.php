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
        'post_id', 'country_id', 'minimum_wage'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }
}
