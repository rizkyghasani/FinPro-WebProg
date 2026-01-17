<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction; 
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BudgetRequest;
use Illuminate\Http\Request; 
use Carbon\Carbon; 

class BudgetController extends Controller
{

    public function index(Request $request) 
    {
        $user = Auth::user();

        // --- 1. Tentukan Periode Waktu Filter ---
        
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
            
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now()->endOfMonth();

        // 2. Ambil Anggaran yang AKTIF dalam periode filter
        $budgets = $user->budgets()
                        ->with('category')
                        ->where('start_date', '<=', $endDate->toDateString())
                        ->where('end_date', '>=', $startDate->toDateString())
                        ->latest('end_date')
                        ->get();

        $totalLimit = 0;
        $totalUsed = 0;
        
        foreach ($budgets as $budget) {
            
            $overlapStart = max($startDate, Carbon::parse($budget->start_date));
            $overlapEnd = min($endDate, Carbon::parse($budget->end_date));
            
            $usedAmount = $user->transactions()
                                ->where('type', 'expense')
                                ->where('category_id', $budget->category_id)
                                ->whereBetween('date', [$overlapStart->toDateString(), $overlapEnd->toDateString()])
                                ->sum('amount');
            
            $progressUsed = $user->transactions()
                                 ->where('type', 'expense')
                                 ->where('category_id', $budget->category_id)
                                 ->whereBetween('date', [$budget->start_date, $budget->end_date])
                                 ->sum('amount');
                                 
            $budget->used = $progressUsed;
            $budget->remaining = $budget->limit - $progressUsed;
            $budget->percentage = ($budget->limit > 0) ? min(100, round(($progressUsed / $budget->limit) * 100)) : 0;
            
            // Akumulasi total untuk Ringkasan
            $totalLimit += $budget->limit;
            $totalUsed += $progressUsed;
        }

        $totalRemaining = $totalLimit - $totalUsed;


        return view('budgets.index', compact(
            'budgets',
            'totalLimit',
            'totalUsed',
            'totalRemaining',
            'startDate',
            'endDate' 
        ));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->where('type', 'expense')->get();
        return view('budgets.create', compact('categories'));
    }

    public function store(BudgetRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id();

        Budget::create($validatedData);

        return redirect()->route('budgets.index')->with('success', __('Anggaran baru berhasil ditambahkan!'));
    }

    public function edit(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Auth::user()->categories()->where('type', 'expense')->get();
        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(BudgetRequest $request, Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $budget->update($request->validated());

        return redirect()->route('budgets.index')->with('success', __('Anggaran berhasil diperbarui.'));
    }

    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $budget->delete();

        return redirect()->route('budgets.index')->with('success', __('Anggaran berhasil dihapus.'));
    }
}