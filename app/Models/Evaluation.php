<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'academic_year',
        'semester',
        'overall_comment',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EvaluationCategory::class, 'category_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(EvaluationResponse::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->responses()->avg('rating') ?? 0;
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }
}
