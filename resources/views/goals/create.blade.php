@extends('layouts.app')

@section('title', __('app.Buat Tujuan Baru'))

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h4 class="mb-0">{{ __('app.Buat Tujuan Keuangan Baru') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('goals.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('app.Nama Tujuan') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="target_amount" class="form-label">{{ __('app.Target Jumlah') }} (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('target_amount') is-invalid @enderror" id="target_amount" name="target_amount" value="{{ old('target_amount') }}" step="0.01" min="10000" placeholder="Misal: 1000000" required>
                        @error('target_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="current_amount" class="form-label">{{ __('app.Jumlah Saat Ini') }} (Rp)</label>
                            <input type="number" class="form-control @error('current_amount') is-invalid @enderror" id="current_amount" name="current_amount" value="{{ old('current_amount', 0) }}" step="0.01" placeholder="Misal: 500000">
                            @error('current_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="due_date" class="form-label">{{ __('app.Batas Waktu Target (Opsional)') }}</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary me-2">{{ __('app.Batal') }}</a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-star me-1"></i> {{ __('app.Simpan Tujuan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection