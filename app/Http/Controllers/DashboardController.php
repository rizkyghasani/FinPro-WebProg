<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request; 
use App\Models\Budget;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard dengan data yang difilter dan data chart.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString(); // Tanggal hari ini

        // --- 1. Tentukan Periode Waktu Filter ---
        
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
            
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now()->endOfMonth();

        // 2. Saldo Total & Statistik Periode yang Difilter (Logika tetap sama)
        $totalIncome = $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpense = $user->transactions()->where('type', 'expense')->sum('amount');
        $currentBalance = $totalIncome - $totalExpense;

        $filteredTransactionsQuery = $user->transactions()
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

        $filteredIncome = $filteredTransactionsQuery->clone()->where('type', 'income')->sum('amount');
        $filteredExpense = $filteredTransactionsQuery->clone()->where('type', 'expense')->sum('amount');
        $filteredNet = $filteredIncome - $filteredExpense;


        // --- 3. Logika Anggaran (DISEMPURNAKAN) ---
        
        // Hanya ambil anggaran yang AKTIF hari ini (Anggaran yang periodenya mencakup tanggal saat ini)
        $budgets = $user->budgets()
                        ->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today)
                        ->get(); 
        
        $budgetSummary = [
            'totalLimit' => $budgets->sum('limit'),
            'totalUsed' => 0,
            'remaining' => 0,
            'percentage' => 0,
            'period' => $budgets->count() > 0 ? $budgets->first()->start_date->isoFormat('D MMM') . ' - ' . $budgets->first()->end_date->isoFormat('D MMM YYYY') : 'Tidak Ada',
        ];

        if ($budgetSummary['totalLimit'] > 0) {
            foreach ($budgets as $budget) {
                // Hitung penggunaan HANYA untuk periode anggaran tersebut
                $usedAmount = $user->transactions()
                                    ->where('type', 'expense')
                                    ->where('category_id', $budget->category_id)
                                    ->whereBetween('date', [$budget->start_date, $budget->end_date])
                                    ->sum('amount');
                $budgetSummary['totalUsed'] += $usedAmount;
            }
            $budgetSummary['remaining'] = $budgetSummary['totalLimit'] - $budgetSummary['totalUsed'];
            $budgetSummary['percentage'] = round(($budgetSummary['totalUsed'] / $budgetSummary['totalLimit']) * 100);
        }
        
        // 4. Data Tujuan (Ringkasan Progres Terdekat) - Logika tetap sama
        $nextGoal = $user->goals()
                         ->where('current_amount', '<', DB::raw('target_amount'))
                         ->orderBy('due_date', 'asc')
                         ->first();
        
        if ($nextGoal) {
             $nextGoal->percentage = ($nextGoal->target_amount > 0) 
                                ? round(($nextGoal->current_amount / $nextGoal->target_amount) * 100) 
                                : 0;
             $nextGoal->remaining = $nextGoal->target_amount - $nextGoal->current_amount;
        }

        // 5. Data untuk Chart (Menggunakan data yang difilter) - Logika tetap sama
        $baseQueryChart = $user->transactions()
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

        $expenseData = $baseQueryChart->clone()
                            ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
                            ->join('categories', 'transactions.category_id', '=', 'categories.id')
                            ->where('transactions.type', 'expense')
                            ->groupBy('categories.name')
                            ->get();

        $incomeData = $baseQueryChart->clone()
                           ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
                           ->join('categories', 'transactions.category_id', '=', 'categories.id')
                           ->where('transactions.type', 'income')
                           ->groupBy('categories.name')
                           ->get();

        $expenseChartData = ['labels' => $expenseData->pluck('name')->toArray(), 'data' => $expenseData->pluck('total')->toArray()];
        $incomeChartData = ['labels' => $incomeData->pluck('name')->toArray(), 'data' => $incomeData->pluck('total')->toArray()];
        
        return view('dashboard', compact(
            'currentBalance', 
            'filteredIncome', 
            'filteredExpense', 
            'filteredNet', 
            'expenseChartData',
            'incomeChartData',
            'budgetSummary', // Diperbarui
            'nextGoal',
            'startDate', 
            'endDate'
        ));
    }
}