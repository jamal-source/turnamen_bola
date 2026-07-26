<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;

class Operator extends Model
{
    protected $fillable = [
        'name',
        'pic_name',
        'phone',
        'district',
        'username',
        'password',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function team(): HasOne
    {
        return $this->hasOne(Team::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
