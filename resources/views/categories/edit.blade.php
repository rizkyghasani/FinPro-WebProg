@extends('layouts.app')

@section('title', __('Edit Kategori'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3">{{ __('Edit Kategori') }}: {{ $category->name }}</h2>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        {{ __('Ubah detail kategori di bawah ini.') }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT') {{-- PENTING: Menggunakan method PUT untuk update --}}

            {{-- Nama Kategori (Field Wajib) --}}
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Nama Kategori') }}</label>
                {{-- Gunakan old('name', $category->name) untuk mempertahankan input lama jika gagal validasi --}}
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tipe Kategori (Pemasukan/Pengeluaran) --}}
            <div class="mb-3">
                <label for="type" class="form-label">{{ __('Tipe') }}</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="">{{ __('Pilih Tipe') }}</option>
                    {{-- Periksa nilai lama atau nilai saat ini dari database --}}
                    <option value="income" {{ old('type', $category->type) == 'income' ? 'selected' : '' }}>{{ __('Pemasukan') }}</option>
                    <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>{{ __('Pengeluaran') }}</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Icon (Opsional) --}}
            <div class="mb-3">
                <label for="icon" class="form-label">{{ __('Ikon (Opsional)') }}</label>
                <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $category->icon) }}">
                <div class="form-text">{{ __('Contoh: bi-cup-fill') }}</div>
                @error('icon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">{{ __('Batal') }}</a>
                <button type="submit" class="btn btn-success">{{ __('Update Kategori') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
