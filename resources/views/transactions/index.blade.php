@extends('layouts.app')

@section('title', __('Daftar Transaksi'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <h2 class="mb-0">{{ __('Daftar Transaksi') }}</h2>
    
    <a href="{{ route('transactions.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i> {{ __('Tambah Transaksi') }}
    </a>
</div>

{{-- Form Filter Tanggal BARU --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">{{ __('Filter Transaksi') }}</h5>
        <form method="GET" action="{{ route('transactions.index') }}" class="d-flex align-items-center flex-wrap gap-2">
            <label for="start_date" class="form-label mb-0">{{ __('Dari:') }}</label>
            <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ $startDate->toDateString() }}" style="width: 150px;">
            
            <label for="end_date" class="form-label mb-0">{{ __('Sampai:') }}</label>
            <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ $endDate->toDateString() }}" style="width: 150px;">
            
            <button type="submit" class="btn btn-sm btn-primary">{{ __('Filter') }}</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('Bulan Ini') }}</a>
        </form>
    </div>
</div>

{{-- Ringkasan Saldo Global --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body py-2">
                <h6 class="card-title mb-0">{{ __('Saldo (Total Semua Waktu):') }} Rp {{ number_format($currentBalance, 2, ',', '.') }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- Ringkasan Filtered Period --}}
<h4 class="h5 mb-3">{{ __('Total Periode:') }} 
    <span class="text-primary">{{ $startDate->isoFormat('D MMM YYYY') }}</span> {{ __('sampai') }} 
    <span class="text-primary">{{ $endDate->isoFormat('D MMM YYYY') }}</span>
</h4>
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-start border-success border-5 h-100">
            <div class="card-body">
                <h5 class="card-title text-success">{{ __('Total Pemasukan') }}</h5>
                <p class="card-text fs-4 fw-bold mb-0">Rp {{ number_format($totalIncome, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-start border-danger border-5 h-100">
            <div class="card-body">
                <h5 class="card-title text-danger">{{ __('Total Pengeluaran') }}</h5>
                <p class="card-text fs-4 fw-bold mb-0">Rp {{ number_format($totalExpense, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>


{{-- Tabel Transaksi --}}
<div class="card shadow-sm">
    <div class="card-body">
        
        {{-- Kontainer Scrollable (Vertical) --}}
        <div class="transaction-list-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Tanggal') }}</th>
                            <th scope="col">{{ __('Deskripsi') }}</th>
                            <th scope="col">{{ __('Kategori') }}</th>
                            <th scope="col">{{ __('Tipe') }}</th>
                            <th scope="col" class="text-end">{{ __('Jumlah') }} (Rp)</th>
                            <th scope="col" class="text-center">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transaction->date)->isoFormat('D MMM YYYY') }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td>
                                <span class="badge bg-{{ $transaction->category->type == 'income' ? 'success' : 'danger' }}">{{ $transaction->category->name }}</span>
                            </td>
                            <td>
                                @if ($transaction->type == 'income')
                                    <span class="badge bg-success">{{ __('Pemasukan') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('Pengeluaran') }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-{{ $transaction->category->type == 'income' ? 'success' : 'danger' }}">
                                {{ number_format($transaction->amount, 2, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-warning me-2" title="{{ __('Edit') }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $transaction->id }}" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                
                                <div class="modal fade" id="deleteModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $transaction->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $transaction->id }}">{{ __('Konfirmasi Hapus') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ __('Apakah Anda yakin ingin menghapus transaksi') }} **"{{ $transaction->description }}"** {{ __('sebesar') }} **Rp {{ number_format($transaction->amount, 2, ',', '.') }}**?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">{{ __('Hapus Permanen') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">{{ __('Belum ada transaksi yang dicatat. Silakan tambah transaksi baru.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection