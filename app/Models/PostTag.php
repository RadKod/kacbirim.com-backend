<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTag extends Model
{
    use HasFactory;

    protected $table = 'post_tag';
    protected $fillable = [
        'post_id', 'tag_id'
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tags::class, 'tag_id', 'id');
    }
}
