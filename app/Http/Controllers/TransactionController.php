<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request; // <-- Wajib diimport
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <-- Wajib diimport

class TransactionController extends Controller
{
    /**
     * Tampilkan daftar transaksi.
     */
    public function index(Request $request) // <-- Terima Request
    {
        $user = Auth::user();

        // --- 1. Tentukan Periode Waktu Filter ---
        
        // Default: Bulan berjalan jika tidak ada filter
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
            
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now()->endOfMonth();

        // 2. Base Query (Filtered Transactions)
        $baseQuery = $user->transactions()
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

        // 3. Ambil Transaksi yang Difilter
        $transactions = $baseQuery->clone()
                             ->with('category') 
                             ->latest('date')
                             ->latest('created_at') 
                             ->get();

        // 4. Hitung Saldo Filtered (Berdasarkan Rentang Tanggal)
        $totalIncome = $baseQuery->clone()->where('type', 'income')->sum('amount');
        $totalExpense = $baseQuery->clone()->where('type', 'expense')->sum('amount');
        
        // Saldo Saat Ini (Total Historis, tidak difilter)
        $currentBalance = $user->transactions()->where('type', 'income')->sum('amount') - $user->transactions()->where('type', 'expense')->sum('amount');

        // Note: Kita kirim Saldo Historis ($currentBalance) dan total filter ($totalIncome, $totalExpense)

        return view('transactions.index', compact(
            'transactions', 
            'currentBalance', 
            'totalIncome', 
            'totalExpense',
            'startDate', // <-- Kirim Tanggal
            'endDate' // <-- Kirim Tanggal
        ));
    }
    /**
     * Tampilkan form untuk membuat transaksi baru.
     */
    public function create()
    {
        // Ambil semua kategori milik user
        $categories = Auth::user()->categories()->get();
        return view('transactions.create', compact('categories'));
    }

    /**
     * Simpan transaksi baru ke database.
     */
    public function store(TransactionRequest $request)
    {
        // Ambil semua data yang telah divalidasi
        $validatedData = $request->validated();
        
        // Tambahkan user_id dari user yang sedang login
        $validatedData['user_id'] = Auth::id();

        // Buat transaksi
        Transaction::create($validatedData);

        return redirect()->route('transactions.index')->with('success', __('Transaksi baru berhasil ditambahkan!'));
    }

    /**
     * Tampilkan form untuk mengedit transaksi.
     */
    public function edit(Transaction $transaction)
    {
        // Kebijakan: Pastikan user hanya bisa mengedit transaksinya sendiri
        if ($transaction->user_id !== Auth::id()) {
            abort(403, __('Anda tidak memiliki akses untuk mengedit transaksi ini.'));
        }

        $categories = Auth::user()->categories()->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Perbarui transaksi di database.
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        // Kebijakan: Pastikan user hanya bisa mengedit transaksinya sendiri
        if ($transaction->user_id !== Auth::id()) {
            abort(403, __('Anda tidak memiliki akses untuk memperbarui transaksi ini.'));
        }

        $transaction->update($request->validated());

        return redirect()->route('transactions.index')->with('success', __('Transaksi berhasil diperbarui!'));
    }

    /**
     * Hapus transaksi dari database.
     */
    public function destroy(Transaction $transaction)
    {
        // Kebijakan: Pastikan user hanya bisa menghapus transaksinya sendiri
        if ($transaction->user_id !== Auth::id()) {
            abort(403, __('Anda tidak memiliki akses untuk menghapus transaksi ini.'));
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', __('Transaksi berhasil dihapus.'));
    }
}