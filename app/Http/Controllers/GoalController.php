<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\GoalRequest;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Auth::user()->goals()->latest()->get();

        foreach ($goals as $goal) {
            $goal->percentage = ($goal->target_amount > 0) 
                                ? round(($goal->current_amount / $goal->target_amount) * 100) 
                                : 0;
            $goal->remaining = $goal->target_amount - $goal->current_amount;
        }

        return view('goals.index', compact('goals'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function store(GoalRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id();

        $validatedData['current_amount'] = $validatedData['current_amount'] ?? 0;

        Goal::create($validatedData);

        return redirect()->route('goals.index')->with('success', __('Tujuan keuangan berhasil dibuat!'));
    }

    public function edit(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('goals.edit', compact('goal'));
    }

    public function update(GoalRequest $request, Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validatedData = $request->validated();
        $validatedData['current_amount'] = $validatedData['current_amount'] ?? 0;

        $goal->update($validatedData);

        return redirect()->route('goals.index')->with('success', __('Tujuan keuangan berhasil diperbarui!'));
    }

    public function destroy(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $goal->delete();

        return redirect()->route('goals.index')->with('success', __('Tujuan keuangan berhasil dihapus.'));
    }
}