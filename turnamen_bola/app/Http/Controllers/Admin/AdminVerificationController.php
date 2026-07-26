<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVerificationController extends Controller
{
    public function verify(Request $request, Player $player)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,pending'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $ageValid = $player->checkAgeValidity();

        PlayerVerification::updateOrCreate(
            ['player_id' => $player->id],
            [
                'status' => $validated['status'],
                'age_valid' => $ageValid,
                'notes' => $validated['notes'] ?? null,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]
        );

        return back()->with('success', "Status verifikasi untuk pemain {$player->name} berhasil diperbarui.");
    }

    public function autoVerifyAll()
    {
        $players = Player::with(['ageCategory', 'verification'])->get();
        $count = 0;

        foreach ($players as $player) {
            $ageValid = $player->checkAgeValidity();
            if ($ageValid) {
                PlayerVerification::updateOrCreate(
                    ['player_id' => $player->id],
                    [
                        'status' => 'auto_approved',
                        'age_valid' => true,
                        'verified_by' => Auth::id(),
                        'verified_at' => now(),
                    ]
                );
                $count++;
            }
        }

        return back()->with('success', "Sistem berhasil memverifikasi otomatis {$count} pemain sesuai kriteria usia.");
    }
}
