@extends('layouts.app')

@section('title', __('Edit Anggaran'))

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h4 class="mb-0">{{ __('Edit Anggaran') }}</h4>
                <p class="text-muted mb-0">{{ __('Mengubah batas dan periode anggaran untuk') }} **{{ $budget->category->name }}**</p>
            </div>
            <div class="card-body">
                <form action="{{ route('budgets.update', $budget) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Batas (Limit) Anggaran --}}
                    <div class="mb-3">
                        <label for="limit" class="form-label">{{ __('Batas Anggaran') }} (Rp) <span class="text-danger">*</span></label>
                        <input 
                            type="number" 
                            class="form-control @error('limit') is-invalid @enderror" 
                            id="limit" 
                            name="limit" 
                            value="{{ old('limit', $budget->limit) }}" 
                            step="0.01" 
                            min="1" 
                            placeholder="Misal: 500000" 
                            required 
                            autofocus
                        >
                        @error('limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Kategori Pengeluaran (Disabled saat Edit) --}}
                    <div class="mb-3">
                        <label for="category_id" class="form-label">{{ __('Kategori') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" disabled>
                            {{-- Tampilkan kategori saat ini saja --}}
                            <option value="{{ $budget->category_id }}" selected>{{ $budget->category->name }} ({{ __('Pengeluaran') }})</option>
                        </select>
                        <input type="hidden" name="category_id" value="{{ $budget->category_id }}">
                        <div class="form-text text-danger">{{ __('Kategori tidak dapat diubah setelah anggaran dibuat.') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        {{-- Tanggal Mulai --}}
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">{{ __('Periode Mulai') }} <span class="text-danger">*</span></label>
                            <input 
                                type="date" 
                                class="form-control @error('start_date') is-invalid @enderror" 
                                id="start_date" 
                                name="start_date" 
                                value="{{ old('start_date', $budget->start_date) }}" 
                                required
                            >
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Berakhir --}}
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">{{ __('Periode Berakhir') }} <span class="text-danger">*</span></label>
                            <input 
                                type="date" 
                                class="form-control @error('end_date') is-invalid @enderror" 
                                id="end_date" 
                                name="end_date" 
                                value="{{ old('end_date', $budget->end_date) }}" 
                                required
                            >
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('budgets.index') }}" class="btn btn-outline-secondary me-2">{{ __('Batal') }}</a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-arrow-repeat me-1"></i> {{ __('Perbarui Anggaran') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
