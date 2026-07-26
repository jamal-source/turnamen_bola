<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerDocument extends Model
{
    protected $fillable = [
        'player_id',
        'type',
        'file_path',
        'original_name',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'akta' => 'Akta Kelahiran',
            'kk' => 'Kartu Keluarga',
            'kia' => 'KIA / KTP',
            'foto' => 'Foto Pemain',
            default => ucfirst($this->type),
        };
    }
}
