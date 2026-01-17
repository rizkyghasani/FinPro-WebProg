@extends('layouts.app')

@section('title', __('app.Manajemen Kategori'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3">{{ __('app.Daftar Kategori Keuangan') }}</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> {{ __('Tambah Kategori') }}
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Nama Kategori') }}</th>
                        <th>{{ __('Tipe') }}</th>
                        <th>{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <span class="badge 
                                @if($category->type === 'income') 
                                    bg-success 
                                @else 
                                    bg-danger 
                                @endif">
                                {{ ucfirst($category->type) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning me-2">
                                {{ __('Edit') }}
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('Hapus') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ __('Belum ada kategori yang ditambahkan.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
