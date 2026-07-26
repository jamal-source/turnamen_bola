<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $event = Event::active() ?? new Event([
            'name' => 'Piala Disdikpora Grassroot Regional Kebumen 2026',
            'organizer' => 'Dinas Pendidikan, Kepemudaan, dan Olahraga Kab. Kebumen',
            'location' => 'Stadion Chandradimuka Kebumen',
            'season' => '2026/2027',
        ]);

        $categories = AgeCategory::all();

        return view('admin.settings.index', compact('event', 'categories'));
    }

    public function updateEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'organizer' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'season' => ['nullable', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $event = Event::active() ?? new Event;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo_path'] = $path;
        }

        $event->fill($validated);
        $event->is_active = true;
        $event->save();

        return back()->with('success', 'Identitas dan logo turnamen berhasil diperbarui.');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'max_birth_year' => ['required', 'integer', 'min:1990', 'max:2050'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        AgeCategory::create($validated);

        return back()->with('success', 'Kategori kelompok usia berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, AgeCategory $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'max_birth_year' => ['required', 'integer', 'min:1990', 'max:2050'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        $category->update($validated);

        return back()->with('success', 'Kategori kelompok usia berhasil diperbarui.');
    }

    public function destroyCategory(AgeCategory $category)
    {
        $category->delete();

        return back()->with('success', 'Kategori kelompok usia berhasil dihapus.');
    }

    public function downloadBackup(): BinaryFileResponse
    {
        $dbPath = database_path('database.sqlite');

        if (! File::exists($dbPath)) {
            File::put($dbPath, '');
        }

        $backupFileName = 'backup_turnamen_bola_'.date('Ymd_His').'.sqlite';

        return response()->download($dbPath, $backupFileName, [
            'Content-Type' => 'application/x-sqlite3',
        ]);
    }

    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'max:51200'],
        ]);

        $file = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $dbPath = database_path('database.sqlite');

        if (in_array($extension, ['sql', 'txt'])) {
            try {
                $sqlContent = File::get($file->getRealPath());
                DB::connection()->getPdo()->exec('PRAGMA foreign_keys = OFF;');
                DB::unprepared($sqlContent);
                DB::connection()->getPdo()->exec('PRAGMA foreign_keys = ON;');
            } catch (\Throwable $e) {
                return back()->withErrors(['backup_file' => 'Gagal memproses file SQL dump: '.$e->getMessage()]);
            }
        } else {
            File::copy($file->getRealPath(), $dbPath);
        }

        return back()->with('success', 'Basis data (database) berhasil di-import dari file cadangan.');
    }
}
