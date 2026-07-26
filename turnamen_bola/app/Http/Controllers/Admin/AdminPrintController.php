<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminPrintController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::with('ageCategory')->get();
        $selectedTeamId = $request->input('team_id', $teams->first()?->id);
        $documentType = $request->input('type', 'buku-tim');

        $event = Event::active();

        $team = null;
        if ($selectedTeamId) {
            $team = Team::with(['operator', 'ageCategory', 'players.verification', 'players.documents'])->find($selectedTeamId);
        }

        return view('admin.print.index', compact('teams', 'team', 'selectedTeamId', 'documentType', 'event'));
    }
}
