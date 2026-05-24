<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'semester',
        'is_active',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function current(): ?self
    {
        return static::active()->latest('started_at')->first();
    }

    public function nextPeriod(): array
    {
        [$startYear, $endYear] = array_map('intval', explode('-', $this->academic_year));

        return match ($this->semester) {
            'First Semester'  => [
                'academic_year' => $this->academic_year,
                'semester'      => 'Second Semester',
            ],
            'Second Semester' => [
                'academic_year' => $this->academic_year,
                'semester'      => 'Summer',
            ],
            'Summer'          => [
                'academic_year' => ($startYear + 1) . '-' . ($endYear + 1),
                'semester'      => 'First Semester',
            ],
            default           => [
                'academic_year' => $this->academic_year,
                'semester'      => 'First Semester',
            ],
        };
    }

    public function getLabelAttribute(): string
    {
        return 'A.Y. ' . $this->academic_year . ' · ' . $this->semester;
    }
}
