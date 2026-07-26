<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\MatchSchedule;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
        $categories = AgeCategory::all();
        $teams = Team::all();

        $query = MatchSchedule::with(['ageCategory', 'homeTeam', 'awayTeam']);

        if ($catId = $request->input('category_id')) {
            $query->where('age_category_id', $catId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('homeTeam', fn ($t) => $t->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('awayTeam', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        $schedules = $query->orderBy('match_date')->orderBy('match_time')->paginate(10)->withQueryString();

        return view('admin.schedule.index', compact('schedules', 'categories', 'teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'age_category_id' => ['required', 'exists:age_categories,id'],
            'home_team_id' => ['required', 'exists:teams,id', 'different:away_team_id'],
            'away_team_id' => ['required', 'exists:teams,id', 'different:home_team_id'],
            'round' => ['required', 'in:penyisihan,8besar,semifinal,final,perebutan_juara3'],
            'group_name' => ['nullable', 'string', 'max:50'],
            'match_date' => ['required', 'date'],
            'match_time' => ['required'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        MatchSchedule::create($validated);

        return redirect()->route('admin.schedule.index')->with('success', 'Jadwal pertandingan berhasil dibuat.');
    }

    public function update(Request $request, MatchSchedule $schedule)
    {
        $validated = $request->validate([
            'age_category_id' => ['required', 'exists:age_categories,id'],
            'round' => ['required', 'in:penyisihan,8besar,semifinal,final,perebutan_juara3'],
            'group_name' => ['nullable', 'string', 'max:50'],
            'match_date' => ['required', 'date'],
            'match_time' => ['required'],
            'location' => ['required', 'string', 'max:255'],
            'home_score' => ['nullable', 'integer', 'min:0'],
            'away_score' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:scheduled,ongoing,finished,cancelled'],
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedule.index')->with('success', 'Jadwal pertandingan berhasil diperbarui.');
    }

    public function destroy(MatchSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedule.index')->with('success', 'Jadwal pertandingan berhasil dihapus.');
    }
}
