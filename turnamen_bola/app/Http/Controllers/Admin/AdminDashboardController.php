<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\MatchSchedule;
use App\Models\Operator;
use App\Models\Player;
use App\Models\PlayerVerification;
use App\Models\Team;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalTeams = Team::count();
        $totalPlayers = Player::count();
        $totalOperators = Operator::count();

        $ku10 = AgeCategory::where('name', 'KU-10')->first();
        $ku12 = AgeCategory::where('name', 'KU-12')->first();

        $ku10Count = $ku10 ? Player::where('age_category_id', $ku10->id)->count() : 0;
        $ku12Count = $ku12 ? Player::where('age_category_id', $ku12->id)->count() : 0;

        // Verification statistics
        $approvedCount = PlayerVerification::whereIn('status', ['approved', 'auto_approved'])->count();
        $pendingCount = PlayerVerification::where('status', 'pending')->count();
        $rejectedCount = PlayerVerification::where('status', 'rejected')->count();

        $approvedPercent = $totalPlayers > 0 ? round(($approvedCount / $totalPlayers) * 100, 1) : 0;
        $pendingPercent = $totalPlayers > 0 ? round(($pendingCount / $totalPlayers) * 100, 1) : 0;
        $rejectedPercent = $totalPlayers > 0 ? round(($rejectedCount / $totalPlayers) * 100, 1) : 0;

        $recentMatches = MatchSchedule::with(['homeTeam', 'awayTeam', 'ageCategory'])
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->take(5)
            ->get();

        $recentActivities = Player::with(['team', 'ageCategory', 'verification'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTeams',
            'totalPlayers',
            'totalOperators',
            'ku10Count',
            'ku12Count',
            'approvedCount',
            'pendingCount',
            'rejectedCount',
            'approvedPercent',
            'pendingPercent',
            'rejectedPercent',
            'recentMatches',
            'recentActivities'
        ));
    }
}
