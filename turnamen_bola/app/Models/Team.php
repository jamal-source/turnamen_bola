<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = [
        'operator_id',
        'age_category_id',
        'name',
        'logo_path',
        'district',
        'jersey_color',
        'manager_name',
        'manager_phone',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path) {
            return asset('storage/'.$this->logo_path);
        }

        return null;
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(MatchSchedule::class, 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(MatchSchedule::class, 'away_team_id');
    }

    public function getVerificationSummaryAttribute(): array
    {
        $players = $this->players()->with('verification')->get();
        $total = $players->count();
        $approved = $players->filter(fn ($p) => in_array($p->verification?->status, ['approved', 'auto_approved']))->count();
        $pending = $players->filter(fn ($p) => $p->verification?->status === 'pending' || ! $p->verification)->count();
        $rejected = $players->filter(fn ($p) => $p->verification?->status === 'rejected')->count();

        return compact('total', 'approved', 'pending', 'rejected');
    }
}
