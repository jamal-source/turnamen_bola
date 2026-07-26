<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchSchedule extends Model
{
    protected $fillable = [
        'age_category_id',
        'home_team_id',
        'away_team_id',
        'round',
        'group_name',
        'match_date',
        'match_time',
        'location',
        'home_score',
        'away_score',
        'status',
    ];

    protected $casts = [
        'match_date' => 'date',
    ];

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function getRoundLabelAttribute(): string
    {
        return match ($this->round) {
            'penyisihan' => 'Babak Penyisihan',
            '8besar' => 'Babak 8 Besar',
            'semifinal' => 'Semifinal',
            'final' => 'Final',
            'perebutan_juara3' => 'Perebutan Juara 3',
            default => ucfirst($this->round),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'Terjadwal',
            'ongoing' => 'Berlangsung',
            'finished' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
