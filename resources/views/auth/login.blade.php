<x-guest-layout>
    <div class="text-center mb-4">
        <h3 class="h4">{{ __('Masuk ke Akun Anda') }}</h3>
        <p class="text-muted">{{ __('Selamat datang kembali di Money Tracker.') }}</p>
    </div>
    
    @if (session('status'))
        <div class="alert alert-success mb-3" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Kata Sandi') }}</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label" for="remember_me">
                {{ __('Ingat Saya') }}
            </label>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-muted" href="{{ route('password.request') }}">
                    {{ __('Lupa kata sandi?') }}
                </a>
            @endif

            <button type="submit" class="btn btn-primary">
                {{ __('Masuk') }}
            </button>
        </div>
        
        <div class="text-center mt-3">
            <a class="text-sm text-muted" href="{{ route('register') }}">
                {{ __('Belum punya akun? Daftar sekarang.') }}
            </a>
        </div>
    </form>
</x-guest-layout>

