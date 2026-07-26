<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Player;
use App\Models\PlayerDocument;
use App\Models\PlayerVerification;
use App\Models\Team;
use Illuminate\Http\Request;

class OperatorPlayerController extends Controller
{
    public function index(Request $request)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->first();
        $categories = AgeCategory::all();

        $players = collect();
        if ($team) {
            $players = Player::where('team_id', $team->id)
                ->with(['ageCategory', 'verification', 'documents'])
                ->get();
        }

        return view('operator.datapemain', compact('operator', 'team', 'players', 'categories'));
    }

    public function store(Request $request)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->first();

        if (! $team) {
            return back()->with('error', 'Tim SSB belum disetup. Harap update profil tim terlebih dahulu.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:20', 'unique:players,nik'],
            'birth_date' => ['required', 'date'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'jersey_number' => ['required', 'integer', 'min:1', 'max:99'],
            'position' => ['required', 'string', 'max:50'],
            'age_category_id' => ['required', 'exists:age_categories,id'],
            'file_akta' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'file_kk' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'file_foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $regNumber = Player::generateRegistrationNumber($validated['age_category_id'], date('Y'));

        $player = Player::create([
            'team_id' => $team->id,
            'age_category_id' => $validated['age_category_id'],
            'name' => $validated['name'],
            'nik' => $validated['nik'],
            'birth_date' => $validated['birth_date'],
            'birth_place' => $validated['birth_place'] ?? 'Kebumen',
            'jersey_number' => $validated['jersey_number'],
            'position' => $validated['position'],
            'registration_number' => $regNumber,
        ]);

        // Upload documents
        foreach (['file_akta' => 'akta', 'file_kk' => 'kk', 'file_foto' => 'foto'] as $fileKey => $docType) {
            if ($request->hasFile($fileKey)) {
                $path = $request->file($fileKey)->store("documents/{$player->id}", 'public');
                PlayerDocument::create([
                    'player_id' => $player->id,
                    'type' => $docType,
                    'file_path' => $path,
                    'original_name' => $request->file($fileKey)->getClientOriginalName(),
                ]);
            }
        }

        // Auto Verification Check
        $ageValid = $player->checkAgeValidity();
        PlayerVerification::create([
            'player_id' => $player->id,
            'status' => $ageValid ? 'auto_approved' : 'pending',
            'age_valid' => $ageValid,
            'notes' => $ageValid ? 'Terverifikasi otomatis dari tanggal lahir.' : 'Menunggu verifikasi manual panitia.',
        ]);

        return redirect()->route('operator.datapemain')->with('success', 'Data pemain berhasil ditambahkan.');
    }

    public function update(Request $request, Player $player)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->first();

        if (! $team || $player->team_id !== $team->id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:20', 'unique:players,nik,'.$player->id],
            'birth_date' => ['required', 'date'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'jersey_number' => ['required', 'integer', 'min:1', 'max:99'],
            'position' => ['required', 'string', 'max:50'],
            'age_category_id' => ['required', 'exists:age_categories,id'],
            'file_akta' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'file_kk' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'file_foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $player->update([
            'name' => $validated['name'],
            'nik' => $validated['nik'],
            'birth_date' => $validated['birth_date'],
            'birth_place' => $validated['birth_place'] ?? $player->birth_place,
            'jersey_number' => $validated['jersey_number'],
            'position' => $validated['position'],
            'age_category_id' => $validated['age_category_id'],
        ]);

        // Update / upload dokumen baru jika ada
        foreach (['file_akta' => 'akta', 'file_kk' => 'kk', 'file_foto' => 'foto'] as $fileKey => $docType) {
            if ($request->hasFile($fileKey)) {
                $path = $request->file($fileKey)->store("documents/{$player->id}", 'public');
                PlayerDocument::updateOrCreate(
                    ['player_id' => $player->id, 'type' => $docType],
                    [
                        'file_path' => $path,
                        'original_name' => $request->file($fileKey)->getClientOriginalName(),
                    ]
                );
            }
        }

        // Re-check age validity after update
        $player->refresh();
        $player->load('ageCategory');
        $ageValid = $player->checkAgeValidity();

        PlayerVerification::updateOrCreate(
            ['player_id' => $player->id],
            [
                'age_valid' => $ageValid,
                'status' => $ageValid ? 'auto_approved' : 'pending',
                'notes' => $ageValid ? 'Terverifikasi otomatis dari tanggal lahir.' : 'Menunggu verifikasi manual panitia.',
            ]
        );

        return redirect()->route('operator.datapemain')->with('success', "Data pemain {$player->name} berhasil diperbarui.");
    }

    public function destroy(Request $request, Player $player)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->first();

        if ($player->team_id !== $team->id) {
            abort(403, 'Akses ditolak.');
        }

        $player->delete();

        return redirect()->route('operator.datapemain')->with('success', 'Data pemain berhasil dihapus.');
    }
}
