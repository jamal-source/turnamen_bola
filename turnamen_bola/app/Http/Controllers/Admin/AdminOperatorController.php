<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminOperatorController extends Controller
{
    public function index(Request $request)
    {
        $query = Operator::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('pic_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        $operators = $query->paginate(10)->withQueryString();
        $totalOperators = Operator::count();
        $activeCount = Operator::whereNotNull('last_login_at')->count();
        $inactiveCount = Operator::whereNull('last_login_at')->count();

        return view('admin.operators.index', compact('operators', 'totalOperators', 'activeCount', 'inactiveCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'pic_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'district' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:operators'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'active';

        Operator::create($validated);

        return redirect()->route('admin.operators.index')->with('success', 'Akun Operator SSB berhasil ditambahkan.');
    }

    public function update(Request $request, Operator $operator)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'pic_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'district' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $operator->update($validated);

        return redirect()->route('admin.operators.index')->with('success', 'Akun Operator SSB berhasil diperbarui.');
    }

    public function destroy(Operator $operator)
    {
        $operator->delete();

        return redirect()->route('admin.operators.index')->with('success', 'Akun Operator SSB berhasil dihapus.');
    }
}
