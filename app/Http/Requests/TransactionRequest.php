<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Category;

class TransactionRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        // Kriteria Keamanan: Hanya user terotentikasi yang boleh membuat transaksi.
        return Auth::check();
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     */
    public function rules(): array
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();

        return [
            // amount: Wajib, harus berupa angka, minimal 1, dengan 2 angka di belakang koma (decimal).
            'amount' => 'required|numeric|min:1|decimal:0,2',
            
            // type: Wajib, nilainya harus 'income' atau 'expense'.
            'type' => ['required', Rule::in(['income', 'expense'])],

            // description: Wajib, string, maksimal 255 karakter.
            'description' => 'required|string|max:255',

            // date: Wajib, harus berupa tanggal yang valid, dan tidak boleh di masa depan.
            'date' => 'required|date|before_or_equal:today',

            // category_id: Wajib, harus ada di tabel categories, dan hanya kategori
            // yang dimiliki oleh user yang sedang login ($userId) yang valid.
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }),
            ],
        ];
    }
    
    /**
     * Kustomisasi pesan validasi (Opsional, untuk tampilan yang lebih ramah).
     */
    public function messages(): array
    {
        return [
            'amount.required' => __('Jumlah transaksi wajib diisi.'),
            'amount.numeric' => __('Jumlah harus berupa angka.'),
            'amount.min' => __('Jumlah transaksi harus minimal 1.'),
            'type.required' => __('Tipe transaksi (Pemasukan/Pengeluaran) wajib dipilih.'),
            'type.in' => __('Tipe transaksi tidak valid.'),
            'date.before_or_equal' => __('Tanggal transaksi tidak boleh di masa depan.'),
            'category_id.required' => __('Kategori wajib dipilih.'),
            'category_id.exists' => __('Kategori yang dipilih tidak valid atau bukan milik Anda.'),
        ];
    }
}