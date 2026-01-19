<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function criteria(): HasMany
    {
        return $this->hasMany(EvaluationCriteria::class, 'category_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'category_id');
    }

    public function scopeStandards($query)
    {
        return $query->where('type', 'standard');
    }

    public function scopeOffices($query)
    {
        return $query->where('type', 'office');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
