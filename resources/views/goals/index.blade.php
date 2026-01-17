@extends('layouts.app')

@section('title', __('Manajemen Tujuan'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3">{{ __('Daftar Tujuan Keuangan') }}</h2>
    <a href="{{ route('goals.create') }}" class="btn btn-success">
        <i class="bi bi-star me-1"></i> {{ __('Buat Tujuan Baru') }}
    </a>
</div>

<div class="row">
    @forelse ($goals as $goal)
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title text-uppercase mb-1">{{ $goal->name }}</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('Aksi') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('goals.edit', $goal) }}">{{ __('Edit Tujuan') }}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteGoalModal{{ $goal->id }}">
                                        {{ __('Hapus Tujuan') }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <p class="text-muted small">
                        {{ __('Target Akhir:') }} 
                        <span class="fw-bold text-info">Rp {{ number_format($goal->target_amount, 2, ',', '.') }}</span>
                        @if ($goal->due_date)
                            ({{ __('Batas Waktu:') }} {{ \Carbon\Carbon::parse($goal->due_date)->isoFormat('D MMM YYYY') }})
                        @endif
                    </p>

                    <div class="d-flex justify-content-between small text-muted">
                        <span>{{ __('Telah Terkumpul:') }} <span class="fw-bold text-success">Rp {{ number_format($goal->current_amount, 2, ',', '.') }}</span></span>
                        <span>{{ __('Kurang:') }} <span class="fw-bold text-danger">Rp {{ number_format($goal->remaining, 2, ',', '.') }}</span></span>
                    </div>

                    {{-- Progress Bar --}}
                    @php
                        $progressClass = ($goal->percentage >= 100) ? 'bg-success' : 'bg-primary';
                        $ariaValue = min(100, $goal->percentage);
                    @endphp
                    
                    <div class="progress mt-2 mb-2" role="progressbar" aria-label="Progress" aria-valuenow="{{ $ariaValue }}" aria-valuemin="0" aria-valuemax="100" style="height: 12px;">
                        <div class="progress-bar {{ $progressClass }}" style="width: {{ $ariaValue }}%"></div>
                    </div>

                    <p class="small text-end fw-bold {{ ($goal->percentage >= 100) ? 'text-success' : 'text-primary' }}">
                        {{ number_format($goal->percentage, 0) }}% {{ __('Tercapai') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteGoalModal{{ $goal->id }}" tabindex="-1" aria-labelledby="deleteGoalModalLabel{{ $goal->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteGoalModalLabel{{ $goal->id }}">{{ __('Konfirmasi Hapus') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('Apakah Anda yakin ingin menghapus tujuan') }} "{{ $goal->name }}" {{ __('secara permanen? Semua data tabungan akan hilang.') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                        <form action="{{ route('goals.destroy', $goal) }}" method="POST">
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
                {{ __('Anda belum menetapkan tujuan keuangan apapun. Silakan buat tujuan pertama Anda.') }}
            </div>
        </div>
    @endforelse
</div>
@endsection