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
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'amount' => 'required|numeric|min:1|decimal:0,2',
            
            'type' => ['required', Rule::in(['income', 'expense'])],

            'description' => 'required|string|max:255',

            'date' => 'required|date|before_or_equal:today',

            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }),
            ],
        ];
    }
    
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