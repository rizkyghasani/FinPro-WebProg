@extends('layouts.app')

@section('title', __('Dashboard Utama'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <h2 class="h3 mb-3 mb-md-0">{{ __('Ringkasan Keuangan Anda') }}</h2>

    {{-- Form Filter Tanggal --}}
    <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center flex-wrap gap-2">
        <label for="start_date" class="form-label mb-0">{{ __('Dari:') }}</label>
        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ $startDate->toDateString() }}" style="width: 150px;">
        
        <label for="end_date" class="form-label mb-0">{{ __('Sampai:') }}</label>
        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ $endDate->toDateString() }}" style="width: 150px;">
        
        <button type="submit" class="btn btn-sm btn-primary">{{ __('Filter') }}</button>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">{{ __('Bulan Ini') }}</a>
    </form>
</div>

{{-- 1. Kartu Saldo Global (Tidak Difilter) --}}
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card bg-primary text-white shadow-lg h-100">
            <div class="card-body py-4">
                <h5 class="card-title">{{ __('SALDO TOTAL SAAT INI') }}</h5>
                <p class="card-text fs-2 fw-bolder">Rp {{ number_format($currentBalance, 2, ',', '.') }}</p>
                <p class="card-text small">{{ __('Ringkasan semua waktu') }}</p>
            </div>
        </div>
    </div>

    {{-- Kartu Anggaran dan Tujuan --}}
    <div class="col-md-6 mb-3">
        <div class="row h-100">
            <div class="col-12 mb-6">
                <div class="card shadow-sm border-start border-info border-5 h-100">
                    <div class="card-body">
                        <h6 class="text-info text-uppercase">{{ __('Anggaran Bulan Ini') }}</h6>
                        @if ($budgetSummary['totalLimit'] > 0)
                            <p class="mb-1 small text-muted">{{ $budgetSummary['period'] }}</p>
                            <p class="mb-1 small">{{ __('Batas:') }} Rp {{ number_format($budgetSummary['totalLimit'], 0, ',', '.') }}</p>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $budgetSummary['percentage'] }}%"></div>
                            </div>
                            <p class="text-muted small mb-0">{{ __('Sisa Anggaran:') }} <span class="fw-bold text-success">Rp {{ number_format($budgetSummary['remaining'], 0, ',', '.') }}</span></p>
                        @else
                            <p class="text-muted small mb-0">{{ __('Belum ada anggaran aktif bulan ini.') }}</p>
                            <a href="{{ route('budgets.create') }}" class="btn btn-sm btn-outline-info mt-2">{{ __('Buat Anggaran') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2. Statistik Periode yang Difilter (Menggunakan $filtered...) --}}
<h4 class="h5 mb-3">{{ __('Statistik Periode:') }} 
    <span class="text-primary">{{ $startDate->isoFormat('D MMM YYYY') }}</span> {{ __('sampai') }} 
    <span class="text-primary">{{ $endDate->isoFormat('D MMM YYYY') }}</span>
</h4>
<div class="row mb-4">
    {{-- Kartu Pemasukan --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-start border-success border-5 h-100">
            <div class="card-body">
                <h6 class="text-success text-uppercase">{{ __('Total Pemasukan') }}</h6>
                <p class="fs-4 fw-bold mb-0">Rp {{ number_format($filteredIncome, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    {{-- Kartu Pengeluaran --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-start border-danger border-5 h-100">
            <div class="card-body">
                <h6 class="text-danger text-uppercase">{{ __('Total Pengeluaran') }}</h6>
                <p class="fs-4 fw-bold mb-0">Rp {{ number_format($filteredExpense, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    {{-- Kartu Tabungan Bersih/Net --}}
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-start border-warning border-5 h-100">
            <div class="card-body">
                <h6 class="text-warning text-uppercase">{{ __('Tabungan Bersih') }}</h6>
                <p class="fs-4 fw-bold mb-0">
                    Rp {{ number_format($filteredNet, 2, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- 3. Area Chart.js --}}
<div class="row">
    {{-- Chart Pengeluaran --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">{{ __('Distribusi Pengeluaran') }}</h6>
            </div>
            <div class="card-body">
                <canvas id="expenseChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart Pemasukan --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">{{ __('Distribusi Pemasukan') }}</h6>
            </div>
            <div class="card-body">
                <canvas id="incomeChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil data dari Blade
        const expenseData = @json($expenseChartData);
        const incomeData = @json($incomeChartData);
        
        // ... (Kode JavaScript Chart.js yang sama)
        const colors = [
            '#dc3545', '#ffc107', '#198754', '#0d6efd', '#6f42c1', 
            '#fd7e14', '#20c997', '#adb5bd', '#e83e8c', '#6610f2'
        ];
        
        function getColors(count, reversed = false) {
            let result = [];
            let colorSet = reversed ? [...colors].reverse() : colors;
            for(let i = 0; i < count; i++) {
                result.push(colorSet[i % colorSet.length]);
            }
            return result;
        }

        // --- Chart Pengeluaran ---
        if (expenseData.data.length > 0) {
            new Chart(
                document.getElementById('expenseChart'),
                {
                    type: 'pie',
                    data: {
                        labels: expenseData.labels,
                        datasets: [{
                            data: expenseData.data,
                            backgroundColor: getColors(expenseData.data.length),
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: false }
                        }
                    }
                }
            );
        } else {
            document.getElementById('expenseChart').style.display = 'none';
            document.getElementById('expenseChart').parentNode.innerHTML = '<p class="text-center text-muted">Belum ada data pengeluaran pada periode ini.</p>';
        }

        // --- Chart Pemasukan ---
        if (incomeData.data.length > 0) {
            new Chart(
                document.getElementById('incomeChart'),
                {
                    type: 'pie',
                    data: {
                        labels: incomeData.labels,
                        datasets: [{
                            data: incomeData.data,
                            backgroundColor: getColors(incomeData.data.length, true),
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: false }
                        }
                    }
                }
            );
        } else {
             document.getElementById('incomeChart').style.display = 'none';
             document.getElementById('incomeChart').parentNode.innerHTML = '<p class="text-center text-muted">Belum ada data pemasukan pada periode ini.</p>';
        }
    });
</script>
@endsection