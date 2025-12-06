<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('Tambah Anggaran'))</title>
</head>
<body>
@extends('layouts.app')

@section('title', __('Tambah Anggaran'))

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h4 class="mb-0">{{ __('Buat Anggaran Baru') }}</h4>
                <p class="text-muted mb-0">{{ __('Anggaran hanya berlaku untuk Kategori Pengeluaran.') }}</p>
            </div>
            <div class="card-body">
                <form action="{{ route('budgets.store') }}" method="POST">
                    @csrf
                    
                    {{-- Batas (Limit) Anggaran --}}
                    <div class="mb-3">
                        <label for="limit" class="form-label">{{ __('Batas Anggaran') }} (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('limit') is-invalid @enderror" id="limit" name="limit" value="{{ old('limit') }}" step="0.01" min="1" placeholder="Misal: 500000" required autofocus>
                        @error('limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Kategori Pengeluaran --}}
                    <div class="mb-3">
                        <label for="category_id" class="form-label">{{ __('Kategori') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="" disabled selected>{{ __('Pilih Kategori Pengeluaran') }}</option>
                            @forelse ($categories as $category)
                                <option 
                                    value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>
                            @empty
                                <option value="" disabled>{{ __('Belum ada Kategori Pengeluaran. Buat dulu di menu Kategori.') }}</option>
                            @endforelse
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        {{-- Tanggal Mulai --}}
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">{{ __('Periode Mulai') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-01')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Berakhir --}}
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">{{ __('Periode Berakhir') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-t')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('budgets.index') }}" class="btn btn-outline-secondary me-2">{{ __('Batal') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calendar-plus me-1"></i> {{ __('Buat Anggaran') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
</body>
</html>