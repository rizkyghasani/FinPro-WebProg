@extends('layouts.app')

@section('title', __('Profil Pengguna'))

@section('content')
<h2 class="h3 mb-4">{{ __('Profil Pengguna') }}</h2>

<div class="row">
    <div class="col-md-8 mx-auto">

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">{{ __('Informasi Profil') }}</h5>
                <p class="text-muted mb-0">{{ __('Perbarui informasi profil dan alamat email akun Anda.') }}</p>
            </div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">{{ __('Perbarui Kata Sandi') }}</h5>
                <p class="text-muted mb-0">{{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.') }}</p>
            </div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-danger">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-danger">{{ __('Hapus Akun') }}</h5>
                <p class="text-muted mb-0">{{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}</p>
            </div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection