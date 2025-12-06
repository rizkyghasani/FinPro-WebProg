<p class="mt-4">
    {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengkonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
</p>

<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
    {{ __('Hapus Akun') }}
</button>

<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
            @csrf
            @method('delete')
            
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmUserDeletionModalLabel">{{ __('Hapus Akun Secara Permanen') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <p>
                    {{ __('Apakah Anda yakin ingin menghapus akun Anda? Semua data yang terkait akan dihapus secara permanen.') }}
                </p>

                <p>
                    {{ __('Masukkan kata sandi Anda untuk mengkonfirmasi penghapusan akun secara permanen.') }}
                </p>

                <div class="mb-3">
                    <label for="password" class="form-label visually-hidden">{{ __('Kata Sandi') }}</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control @error('password', 'confirmingUserDeletion') is-invalid @enderror"
                        placeholder="{{ __('Kata Sandi') }}"
                    >
                    @error('password', 'confirmingUserDeletion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                <button type="submit" class="btn btn-danger">{{ __('Hapus Akun') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Script untuk menampilkan modal error secara otomatis jika validasi gagal --}}
@if ($errors->hasAny(['password'], 'confirmingUserDeletion'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
            modal.show();
        });
    </script>
@endif