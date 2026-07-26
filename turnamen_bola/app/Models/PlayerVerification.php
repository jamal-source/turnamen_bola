<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerVerification extends Model
{
    protected $fillable = [
        'player_id',
        'status',
        'age_valid',
        'notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'age_valid' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved', 'auto_approved' => '<span class="badge bg-success">Lolos Verifikasi</span>',
            'pending' => '<span class="badge bg-warning text-dark">Menunggu Verifikasi</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak / Perlu Revisi</span>',
            default => '<span class="badge bg-secondary">Belum Diverifikasi</span>',
        };
    }

    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'auto_approved']);
    }
}
