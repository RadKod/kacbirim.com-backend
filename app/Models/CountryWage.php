<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryWage extends Model
{
    use HasFactory;

    protected $table = 'country_wage';
    protected $fillable = [
        'country_id', 'year', 'wage'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }
}
