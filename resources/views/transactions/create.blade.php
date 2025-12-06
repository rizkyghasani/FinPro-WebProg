@extends('layouts.app')

@section('title', __('Tambah Transaksi'))

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h4 class="mb-0">{{ __('Tambah Transaksi Baru') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="type" class="form-label">{{ __('Tipe Transaksi') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="" disabled selected>{{ __('Pilih Tipe') }}</option>
                                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>{{ __('Pemasukan') }}</option>
                                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>{{ __('Pengeluaran') }}</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="date" class="form-label">{{ __('Tanggal') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">{{ __('Kategori') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="" disabled selected>{{ __('Pilih Kategori') }}</option>
                                @foreach ($categories as $category)
                                    <option 
                                        value="{{ $category->id }}" 
                                        data-type="{{ $category->type }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                                    >
                                        {{ $category->name }} ({{ $category->type == 'income' ? __('Pemasukan') : __('Pengeluaran') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="amount" class="form-label">{{ __('Jumlah') }} (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="1" placeholder="Misal: 50000.50" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Deskripsi/Catatan') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Misal: Gaji bulan ini" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary me-2">{{ __('Batal') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> {{ __('Simpan Transaksi') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk memfilter Kategori berdasarkan Tipe yang dipilih --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category_id');
        const categoryOptions = categorySelect.querySelectorAll('option');

        function filterCategories() {
            const selectedType = typeSelect.value;
            
            // Atur ulang category_id
            categorySelect.value = "";
            
            categoryOptions.forEach(option => {
                const categoryType = option.getAttribute('data-type');
                
                // Tampilkan opsi jika tidak memiliki data-type (opsi default) atau jika tipenya cocok
                if (!categoryType || categoryType === selectedType) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }
        
        // Panggil saat halaman dimuat (jika ada data old('type'))
        filterCategories();

        // Panggil setiap kali tipe transaksi berubah
        typeSelect.addEventListener('change', filterCategories);
    });
</script>
@endsection