<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="mt-4">
    @csrf
    @method('patch')

    {{-- Nama --}}
    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Nama') }}</label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email') }}</label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        {{-- Notifikasi Verifikasi Email (Jika perlu diaktifkan) --}}
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 text-danger">
                {{ __('Alamat email Anda belum diverifikasi.') }}

                <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">
                    {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                </button>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success mt-2 small">
                    {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email Anda.') }}
                </div>
            @endif
        @endif
    </div>

    {{-- Tombol Simpan dan Status Notifikasi --}}
    <div class="d-flex align-items-center gap-4">
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>

        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-success"
            >{{ __('Berhasil disimpan.') }}</p>
        @endif
    </div>
</form>