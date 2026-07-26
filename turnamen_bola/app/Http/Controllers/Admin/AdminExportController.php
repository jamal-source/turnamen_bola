<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExportController extends Controller
{
    public function index()
    {
        $categories = AgeCategory::all();
        $teams = Team::all();

        return view('admin.export.index', compact('categories', 'teams'));
    }

    public function exportExcel(Request $request)
    {
        $catId = $request->input('category_id');
        $teamId = $request->input('team_id');

        $query = Player::with(['team', 'ageCategory', 'verification']);

        if ($catId) {
            $query->where('age_category_id', $catId);
        }
        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        $players = $query->get();

        $filename = 'Data_Pemain_Turnamen_'.date('Ymd_His').'.csv';

        $headers = [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['No', 'No. Registrasi', 'Nama Pemain', 'NIK', 'Tgl Lahir', 'Kategori', 'SSB / Tim', 'No. Punggung', 'Posisi', 'Status Verifikasi'];

        $callback = function () use ($players, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM to fix UTF-8 in Excel
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($players as $index => $player) {
                $statusLabel = match ($player->verification?->status) {
                    'approved', 'auto_approved' => 'Lolos Verifikasi',
                    'rejected' => 'Perlu Revisi / Ditolak',
                    default => 'Menunggu Verifikasi',
                };

                fputcsv($file, [
                    $index + 1,
                    $player->registration_number ?? '-',
                    $player->name,
                    $player->nik ?? '-',
                    $player->birth_date ? $player->birth_date->format('d/m/Y') : '-',
                    $player->ageCategory?->name ?? '-',
                    $player->team?->name ?? '-',
                    $player->jersey_number ?? '-',
                    $player->position ?? '-',
                    $statusLabel,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
