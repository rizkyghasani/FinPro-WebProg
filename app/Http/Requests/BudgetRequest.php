<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Category;

class BudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'limit' => 'required|numeric|min:1|decimal:0,2',

            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',

            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->where('type', 'expense'); 
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => __('Kategori yang dipilih tidak valid, atau kategori tersebut adalah Pemasukan (Anggaran hanya berlaku untuk Pengeluaran).'),
            'end_date.after_or_equal' => __('Tanggal berakhir harus sama atau setelah tanggal mulai.'),
            'limit.min' => __('Batas anggaran harus minimal Rp 1.'),
        ];
    }
}