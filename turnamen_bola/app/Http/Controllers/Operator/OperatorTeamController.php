<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\Team;
use Illuminate\Http\Request;

class OperatorTeamController extends Controller
{
    public function showProfile(Request $request)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->with('ageCategory')->first();
        $categories = AgeCategory::all();

        return view('operator.profile', compact('operator', 'team', 'categories'));
    }

    public function updateProfile(Request $request)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'jersey_color' => ['required', 'string', 'max:100'],
            'manager_name' => ['required', 'string', 'max:255'],
            'manager_phone' => ['required', 'string', 'max:50'],
            'age_category_id' => ['required', 'exists:age_categories,id'],
        ]);

        if ($team) {
            $team->update($validated);
        } else {
            Team::create(array_merge($validated, ['operator_id' => $operator->id]));
        }

        // Update operator info too
        $operator->update([
            'name' => $validated['name'],
            'district' => $validated['district'],
            'pic_name' => $validated['manager_name'],
            'phone' => $validated['manager_phone'],
        ]);

        return redirect()->route('operator.profile')->with('success', 'Profil SSB berhasil diperbarui.');
    }
}
