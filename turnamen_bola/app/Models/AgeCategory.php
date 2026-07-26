<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgeCategory extends Model
{
    protected $fillable = [
        'name',
        'max_birth_year',
        'min_birth_year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_birth_year' => 'integer',
        'min_birth_year' => 'integer',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function matchSchedules(): HasMany
    {
        return $this->hasMany(MatchSchedule::class);
    }

    /**
     * Check if a birth year is valid for this category.
     */
    public function isBirthYearValid(int $birthYear): bool
    {
        if ($this->min_birth_year && $birthYear < $this->min_birth_year) {
            return false;
        }

        return $birthYear >= $this->max_birth_year;
    }
}
