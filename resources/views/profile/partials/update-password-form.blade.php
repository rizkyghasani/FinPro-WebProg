<form method="post" action="{{ route('password.update') }}" class="mt-4">
    @csrf
    @method('put')

    {{-- Kata Sandi Saat Ini --}}
    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">{{ __('Kata Sandi Saat Ini') }}</label>
        <input 
            id="update_password_current_password" 
            name="current_password" 
            type="password" 
            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
            autocomplete="current-password"
            required
        >
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kata Sandi Baru --}}
    <div class="mb-3">
        <label for="update_password_password" class="form-label">{{ __('Kata Sandi Baru') }}</label>
        <input 
            id="update_password_password" 
            name="password" 
            type="password" 
            class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
            autocomplete="new-password"
            required
        >
        @error('password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Konfirmasi Kata Sandi Baru --}}
    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">{{ __('Konfirmasi Kata Sandi') }}</label>
        <input 
            id="update_password_password_confirmation" 
            name="password_confirmation" 
            type="password" 
            class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
            autocomplete="new-password"
            required
        >
        @error('password_confirmation', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Tombol Simpan dan Status Notifikasi --}}
    <div class="d-flex align-items-center gap-4">
        <button type="submit" class="btn btn-primary">{{ __('Simpan Kata Sandi Baru') }}</button>

        @if (session('status') === 'password-updated')
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