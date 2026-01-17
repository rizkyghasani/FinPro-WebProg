<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request; 
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 

class TransactionController extends Controller
{
    public function index(Request $request) 
    {
        $user = Auth::user();

        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
            
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now()->endOfMonth();

        $baseQuery = $user->transactions()
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

        $transactions = $baseQuery->clone()
                             ->with('category') 
                             ->latest('date')
                             ->latest('created_at') 
                             ->get();

        $totalIncome = $baseQuery->clone()->where('type', 'income')->sum('amount');
        $totalExpense = $baseQuery->clone()->where('type', 'expense')->sum('amount');
        
        $currentBalance = $user->transactions()->where('type', 'income')->sum('amount') - $user->transactions()->where('type', 'expense')->sum('amount');
        
        return view('transactions.index', compact(
            'transactions', 
            'currentBalance', 
            'totalIncome', 
            'totalExpense',
            'startDate', 
            'endDate' 
        ));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->get();
        return view('transactions.create', compact('categories'));
    }

    public function store(TransactionRequest $request)
    {
        $validatedData = $request->validated();
        
        $validatedData['user_id'] = Auth::id();

        Transaction::create($validatedData);

        return redirect()->route('transactions.index')->with('success', __('Transaksi baru berhasil ditambahkan!'));
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, __('Anda tidak memiliki akses untuk mengedit transaksi ini.'));
        }

        $categories = Auth::user()->categories()->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, __('Anda tidak memiliki akses untuk memperbarui transaksi ini.'));
        }

        $transaction->update($request->validated());

        return redirect()->route('transactions.index')->with('success', __('Transaksi berhasil diperbarui!'));
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, __('Anda tidak memiliki akses untuk menghapus transaksi ini.'));
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', __('Transaksi berhasil dihapus.'));
    }
}