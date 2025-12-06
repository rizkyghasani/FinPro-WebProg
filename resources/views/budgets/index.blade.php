@extends('layouts.app')

@section('title', __('Manajemen Anggaran'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <h2 class="h3 mb-3 mb-md-0">{{ __('Anggaran Pengeluaran') }}</h2>
    <a href="{{ route('budgets.create') }}" class="btn btn-primary">
        <i class="bi bi-calendar-plus me-1"></i> {{ __('Buat Anggaran Baru') }}
    </a>
</div>

{{-- Form Filter Tanggal BARU --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">{{ __('Filter Anggaran') }}</h5>
        <form method="GET" action="{{ route('budgets.index') }}" class="d-flex align-items-center flex-wrap gap-2">
            <label for="start_date" class="form-label mb-0">{{ __('Dari:') }}</label>
            <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ $startDate->toDateString() }}" style="width: 150px;">
            
            <label for="end_date" class="form-label mb-0">{{ __('Sampai:') }}</label>
            <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ $endDate->toDateString() }}" style="width: 150px;">
            
            <button type="submit" class="btn btn-sm btn-primary">{{ __('Filter') }}</button>
            <a href="{{ route('budgets.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('Bulan Ini') }}</a>
        </form>
    </div>
</div>

{{-- Judul Periode --}}
<h4 class="h5 mb-3">{{ __('Anggaran Aktif Periode:') }} 
    <span class="text-primary">{{ $startDate->isoFormat('D MMM YYYY') }}</span> {{ __('sampai') }} 
    <span class="text-primary">{{ $endDate->isoFormat('D MMM YYYY') }}</span>
</h4>

{{-- Ringkasan Total Anggaran --}}
<div class="row mb-4">
    {{-- Total Batas Anggaran (Filterable Total) --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-info h-100">
            <div class="card-body">
                <h5 class="card-title text-info">{{ __('Total Batas Anggaran') }}</h5>
                <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalLimit, 2, ',', '.') }}</p>
                <p class="small text-muted mb-0">{{ __('Total batas anggaran yang aktif dalam periode ini.') }}</p>
            </div>
        </div>
    </div>
    
    {{-- Total Digunakan (Filterable Total) --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-danger h-100">
            <div class="card-body">
                <h5 class="card-title text-danger">{{ __('Total Digunakan') }}</h5>
                <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalUsed, 2, ',', '.') }}</p>
                <p class="small text-muted mb-0">{{ __('Total penggunaan yang terekam dalam periode filter.') }}</p>
            </div>
        </div>
    </div>
    
    {{-- Total Sisa Anggaran --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-success h-100">
            <div class="card-body">
                <h5 class="card-title text-success">{{ __('Sisa Anggaran') }}</h5>
                <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalRemaining, 2, ',', '.') }}</p>
                 <p class="small text-muted mb-0">{{ __('Total batas dikurangi total digunakan.') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Daftar Anggaran --}}
<div class="row">
    @forelse ($budgets as $budget)
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title text-uppercase mb-1">{{ $budget->category->name }}</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('Aksi') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('budgets.edit', $budget) }}">{{ __('Edit Anggaran') }}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteBudgetModal{{ $budget->id }}">
                                        {{ __('Hapus Anggaran') }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <p class="text-muted small">
                        {{ __('Periode Anggaran:') }} {{ $budget->start_date->isoFormat('D MMM YYYY') }} - {{ $budget->end_date->isoFormat('D MMM YYYY') }}
                    </p>

                    <div class="d-flex justify-content-between small text-muted">
                        <span>{{ __('Digunakan (Total Periode):') }} <span class="fw-bold text-danger">Rp {{ number_format($budget->used, 2, ',', '.') }}</span></span>
                        <span>{{ __('Batas:') }} <span class="fw-bold text-info">Rp {{ number_format($budget->limit, 2, ',', '.') }}</span></span>
                    </div>

                    {{-- Progress Bar --}}
                    @php
                        $progressClass = ($budget->percentage >= 100) ? 'bg-danger' : (($budget->percentage > 80) ? 'bg-warning' : 'bg-primary');
                        $ariaValue = min(100, $budget->percentage);
                    @endphp
                    
                    <div class="progress mt-2 mb-2" role="progressbar" aria-label="Usage" aria-valuenow="{{ $ariaValue }}" aria-valuemin="0" aria-valuemax="100" style="height: 12px;">
                        <div class="progress-bar {{ $progressClass }}" style="width: {{ $ariaValue }}%"></div>
                    </div>

                    <p class="small text-end fw-bold {{ ($budget->percentage >= 100) ? 'text-danger' : 'text-success' }}">
                        @if ($budget->percentage > 100)
                            {{ __('MELEBIHI BATAS') }} ({{ number_format($budget->percentage, 0) }}%)
                        @else
                            {{ __('Sisa:') }} Rp {{ number_format($budget->remaining, 2, ',', '.') }}
                            ({{ number_format($budget->percentage, 0) }}% {{ __('Digunakan') }})
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus Anggaran -->
        <div class="modal fade" id="deleteBudgetModal{{ $budget->id }}" tabindex="-1" aria-labelledby="deleteBudgetModalLabel{{ $budget->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteBudgetModalLabel{{ $budget->id }}">{{ __('Konfirmasi Hapus') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('Apakah Anda yakin ingin menghapus anggaran untuk kategori') }} **{{ $budget->category->name }}** {{ __('pada periode ini?') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('Hapus Permanen') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                {{ __('Anda belum menetapkan anggaran apapun. Silakan buat anggaran pertama Anda.') }}
            </div>
        </div>
    @endforelse
</div>
@endsection